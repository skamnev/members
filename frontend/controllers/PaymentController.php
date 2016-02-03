<?php
namespace frontend\controllers;

use frontend\models\User;
use frontend\components\BraintreeForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Payment controller
 */
class PaymentController extends MainController
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['signup', 'index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $postEmail = Yii::$app->session->get('new_member_email');
        $planId = $this->getPlanId();

        if ($this->validateUserActiveByEmail($postEmail)) {
            return Yii::$app->response->redirect(array('site/login'));
        }

        $model = new BraintreeForm();

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->getUser()->getIdentity();
        } else {
            $user = new User();
        }

        if (!empty($user->firstname)) {
            $model->customer_firstName = $user->firstname;
        }
        if (!empty($user->lastname)) {
            $model->customer_lastName = $user->lastname;
        }
        if (!empty($user->email)) {
            $model->customer_email = $user->email;
        } else if (!empty($postEmail)) {
            $model->customer_email = $postEmail;
        }

        if ($model->load(Yii::$app->request->post())) {
            //$user = User::find()->where(['email' => $model->customer_email])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if (!empty($model->customer_firstName) && $model->customer_firstName != $user->firstname) {
                $user->firstname = $model->customer_firstName;
            }
            if (!empty($model->customer_lastName) && $model->customer_lastName != $user->lastname) {
                $user->lastname = $model->customer_lastName;
            }

            $checkEmail = User::find()->where(['email' => $model->customer_email])->andWhere(['not', ['id' => $user->id]])->exists();
            if (!empty($model->customer_email) && $model->customer_email != $user->email && !$checkEmail) {
                $user->email = $model->customer_email;
            }

            if (empty($user->braintree_customer_id)) {//create braintree customer if customer id record does not exists
                $model->setScenario('customer');

                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $customer = $model->send();
                    if ($customer['status']) {
                        $user->braintree_customer_id = $customer['result']->customer->id;

                        if ($this->updateUser($user)) {
                            return Yii::$app->response->redirect(array('payment/thankyou'));
                        } else {
                            Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not update user status. Please contact system administrator'));
                        }
                    }
                }
            } else {// else if customer id record exists
                $model->setScenario('creditCard');

                $model->customerId = $user->braintree_customer_id;

                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $user = User::find()->where(['email' => $model->customer_email])->one();

                    if ($creditCard = $model->send()) {
                        $model->paymentMethodToken = $creditCard['result']->creditCard->token;
                        $model->planId = $planId;

                        $model->setScenario('subscription');
                        if ($subscription = $model->send()) {
                            if ($this->updateUser($user)) {
                                return Yii::$app->response->redirect(array('payment/thankyou'));
                            } else {
                                Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not update user status. Please contact system administrator'));
                            }
                        }
                    }
                }
            }
        }

        $model->setScenario('subscription');

        return $this->render('index', ['model' => $model, 'paymentPlan' => Yii::$app->get('braintree')->plans[$planId]]);
    }

    private function getPlanId() {
        $sessionPlanId = Yii::$app->session->get('payment_plan_id');
        $plans = Yii::$app->get('braintree')->plans;

        if (in_array($sessionPlanId, $plans)) {
            return $sessionPlanId;
        }

        return Yii::$app->get('braintree')->planId;
    }
    public function actionThankyou() {
        return $this->render('thankyou', []);
    }

    public function updateUser($user) {
        $newUser = false;

        //set password if it was empty
        if (Yii::$app->user->isGuest) {
            $password = 'test123';hash('sha512', rand());//@TODOremove this
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->change_password = 1;

            $newUser = true;
        }

        $user->status = User::STATUS_PAID;
        $user->last_payment_date = time();

        if ($user->save()) {
            if ($newUser) {
                \Yii::$app->mailer->compose(['html' => 'signUp-html', 'text' => 'signUp-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($user->email)
                    ->setSubject('Sign Up to ' . \Yii::$app->name)
                    ->send();
            }

            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @return false if user not exists or exists but not active.
     */
    public function validateUserActiveByEmail($email) {
        $userExistsAndActive = User::find()->where(['and', 'email = :email',
                                                    ['in', 'status', [User::STATUS_ACTIVE, User::STATUS_PAID]]],
                                                    [':email'  => $email])
                                            ->count();

        if ($userExistsAndActive) {
            return true;
        }

        return false;
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex_old()
    {
        $model = new BraintreeForm();

        $user = Yii::$app->getUser()->getIdentity();

        if (!empty($user->firstname)) {
            $model->customer_firstName = $user->firstname;
        }
        if (!empty($user->lastname)) {
            $model->customer_lastName = $user->lastname;
        }
        if (!empty($user->email)) {
            $model->customer_email = $user->email;
        }

        if (empty($user->braintree_customer_id)) {//create braintree customer if customer id record does not exists
            $model->setScenario('customer');

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $customer = $model->send();
                if ($customer['status']) {
                    $user->braintree_customer_id = $customer['result']->customer->id;

                    if ($user->save()) {
                        //@TODO add logic to delete user on Braintree side if can not save customer id
                    }
                }
            }
        } else {// else if customer id record exists
            $model->setScenario('creditCard');

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->customerId = $user->braintree_customer_id;

                if ($creditCard = $model->send()) {
                    $model->paymentMethodToken = $creditCard['result']->creditCard->token;
                    $model->planId = Yii::$app->get('braintree')->planId;

                    $model->setScenario('subscription');
                    if ($subscription = $model->send()) {
                        $user = Yii::$app->getUser()->getIdentity();

                        if (!empty($model->customer_firstName) && $model->customer_firstName != $user->firstname) {
                            $user->firstname = $model->customer_firstName;
                        }
                        if (!empty($model->customer_lastName) && $model->customer_lastName != $user->lastname) {
                            $user->lastname = $model->customer_lastName;
                        }

                        $checkEmail = User::find()->where(['email' => $model->customer_email])->andWhere(['not', ['id' => $user->id]])->exists();
                        if (!empty($model->customer_email) && $model->customer_email != $user->email && !$checkEmail) {
                            $user->email = $model->customer_email;
                        }

                        $user->status = User::STATUS_PAID;

                        if ($user->save()) {
                            return Yii::$app->response->redirect(array('mapping'));
                        } else {
                            Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not update user status. Please contact system administrator'));
                        }
                    }
                }
            }
        }

        $model->setScenario('subscription');
        return $this->render('index', ['model' => $model]);
    }
}