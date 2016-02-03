<?php
namespace frontend\controllers;

use backend\models\FieldsTypes;
use backend\models\GeneralSettings;
use backend\models\CmsPages;
use backend\models\MappingCategories;
use backend\models\MappingQuestions;
use backend\models\MappingQuestionsToOptions;
use backend\models\MembersAttributes;
use frontend\models\ChangePasswordForm;
use frontend\models\MembersAttributesAnswers;
use backend\models\MembersAttributesToOptions;
use frontend\models\MembersQuestionsAnswers;
//use backend\models\MembersMappingAnswers;
use frontend\components\MappingValidator;
use frontend\models\User;
use frontend\components\BraintreeForm;
use Yii;
//use frontend\models\User;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
//use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\BaseVarDumper;
//use yii\data\ActiveDataProvider;



//require(__DIR__ . '/../../vendor/autoload.php');
//require(__DIR__ . '/../../vendor/braintree/braintree_php/lib/Braintree/Transaction.php');

/**
 * Site controller
 */
class SiteController extends MainController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'backend', 'pay'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'backend', 'pay', 'changepassword'],
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
        return $this->render('index');
    }

    public function actionChangepassword(){
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $model = new ChangePasswordForm();
        $modeluser =Yii::$app->user->identity;

        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                try{
                    $modeluser->setPassword($model->newpass);
                    $modeluser->generateAuthKey();
                    $modeluser->change_password = 0;

                    if($modeluser->save()){
                        Yii::$app->getSession()->setFlash(
                            'success',Yii::t('frontend','Password changed')
                        );
                        return $this->redirect(['login']);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error',Yii::t('frontend','Password not changed')
                        );
                        return $this->redirect(['index']);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('changepassword',[
                        'model'=>$model
                    ]);
                }
            }else{
                return $this->render('changepassword',[
                    'model'=>$model
                ]);
            }
        }else{
            return $this->render('changepassword',[
                'model'=>$model
            ]);
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionPay () {
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

            if ($model->load(Yii::$app->request->post())) {
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

            if ($model->load(Yii::$app->request->post())) {
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
        return $this->render('pay', ['model' => $model]);
    }

    public function actionMapping_ () {
        if (!Yii::$app->user->isGuest) {
            $steps = Yii::$app->MappingComponent->getSteps();
            print_r($steps);
            $user = Yii::$app->getUser()->getIdentity();
            //@TODO new logic to find out if there are answers
            /*$answers = MembersQuestionsAnswers::find(['member_id' => $user->id])->count();

            if ($answers > 0) {
                $user->status = User::STATUS_ACTIVE;

                if ($user->save()) {
                    return Yii::$app->response->redirect(array('members/index'));
                } else {
                    Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not update user status. Please contact system administrator'));
                }
            }*/

            $settingsModel = GeneralSettings::findOne(['name' => 'mapping_page_id']);
            $pageModel = CmsPages::findOne(['id' => $settingsModel->value]);
            $categoryModel = MappingCategories::findOne(['id' => $pageModel->mapping_id]);

            $questionsAnswers = new MembersQuestionsAnswers($categoryModel->id);

            if (Yii::$app->request->post()) {
                $mappingQuestionsSaved = false;
                echo $questionsAnswers->validate();
                print_r($questionsAnswers->errors);
                if ($questionsAnswers->load(Yii::$app->request->post()) && $questionsAnswers->validate()) {
                    foreach ($questionsAnswers->attributes as $attribute_id => $attribute) {
                        $attributesArray = explode('_', $attribute_id);
                        if (count($attributesArray) > 1) {
                            list($name, $id) = $attributesArray;
                            if (!$model = MembersQuestionsAnswers::findOne(['question_id' => $id, 'member_id' => Yii::$app->getUser()->id])) {
                                $model = new MembersQuestionsAnswers();
                            }

                            $has_option = false;

                            if ($questionsModel = MappingQuestions::findOne($id)) {
                                $type_id = $questionsModel->type_id;
                                $has_option = FieldsTypes::findOne($type_id)->has_options;
                            }

                            if ($has_option) {
                                $model->value = '';
                                $model->option_id = '';
                                if ($optionsModel = MappingQuestionsToOptions::findOne($questionsAnswers->{$attribute_id})) {
                                    $model->value = $optionsModel->title;
                                    $model->option_id = $optionsModel->id;
                                }
                            } else {
                                $model->value = $questionsAnswers->{$attribute_id};
                            }

                            $model->question_id = $id;
                            $model->member_id = Yii::$app->getUser()->id;

                            $model->save();
                        }
                    }
                    $mappingQuestionsSaved = true;
                }

                if ($mappingQuestionsSaved) {
                    $user = Yii::$app->getUser()->getIdentity();
                    //$user->status = User::STATUS_ACTIVE;

                    if ($user->save()) {
                        return Yii::$app->response->redirect(array('members'));
                    } else {
                        Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not save mapping data. Please contact system administrator'));
                    }

                }
            }

            //$categoryModel = MappingCategories::findOne(['id' => $pageModel->mapping_id]);
            $categoryModel = MappingCategories::find()->where(['is_active' => 1])->all();

            //$questionsModel = MappingQuestions::find()->where(['category_id' => $categoryModel->id])->with('fieldsTypes')->all();

            return $this->render('mapping', [
                'questionsAnswers' => $questionsAnswers,
                'pageModel' => $pageModel,
                'categoryModel' => $categoryModel,
                //'questionsModel' => $questionsModel,
            ]);

        } else {
            return Yii::$app->response->redirect(array('site/login'));
        }
    }

    public function actionMapping_old_171115 () {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->getUser()->getIdentity();
            //@TODO new logic to find out if there are answers
            /*$answers = MembersQuestionsAnswers::find(['member_id' => $user->id])->count();

            if ($answers > 0) {
                $user->status = User::STATUS_ACTIVE;

                if ($user->save()) {
                    return Yii::$app->response->redirect(array('members/index'));
                } else {
                    Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not update user status. Please contact system administrator'));
                }
            }*/

            $settingsModel = GeneralSettings::findOne(['name' => 'mapping_page_id']);
            $pageModel = CmsPages::findOne(['id' => $settingsModel->value]);

            $categoryModel = MappingCategories::findOne(['id' => $pageModel->mapping_id]);
            $attributesModel = MembersAttributes::findAll(['id' => $categoryModel->attributes_id]);
            $questionsModel = MappingQuestions::find(['category_id' => $categoryModel->id])->with('fieldsTypes')->all();

            $questionsAnswers = new MembersQuestionsAnswers($pageModel->category_id);
            $attributesAnswers = new MembersAttributesAnswers($categoryModel->attributes_id);

            if (Yii::$app->request->post()) {
                $mappingQuestionsSaved = false;
                $mappingAttributesSaved = false;

                if ($questionsAnswers->load(Yii::$app->request->post()) && $questionsAnswers->validate()) {
                    foreach ($questionsAnswers->attributes as $attribute_id => $attribute) {
                        $attributesArray = explode('_', $attribute_id);
                        if (count($attributesArray) > 1) {
                            list($name, $id) = $attributesArray;
                            if (!$model = MembersQuestionsAnswers::findOne(['question_id' => $id, 'member_id' => Yii::$app->getUser()->id])) {
                                $model = new MembersQuestionsAnswers();
                            }

                            $has_option = false;

                            if ($questionsModel = MappingQuestions::findOne($id)) {
                                $type_id = $questionsModel->type_id;
                                $has_option = FieldsTypes::findOne($type_id)->has_options;
                            }

                            if ($has_option) {
                                $model->value = '';
                                $model->option_id = '';
                                if ($optionsModel = MappingQuestionsToOptions::findOne($questionsAnswers->{$attribute_id})) {
                                    $model->value = $optionsModel->title;
                                    $model->option_id = $optionsModel->id;
                                }
                            } else {
                                $model->value = $questionsAnswers->{$attribute_id};
                            }

                            $model->question_id = $id;
                            $model->member_id = Yii::$app->getUser()->id;

                            $model->save();
                        }
                    }
                    $mappingQuestionsSaved = true;
                }

                if ($attributesAnswers->load(Yii::$app->request->post()) && $attributesAnswers->validate()) {
                    foreach ($attributesAnswers->attributes as $attribute_id => $attribute) {
                        $attributesArray = explode('_', $attribute_id);
                        if (count($attributesArray) > 1) {
                            list($name, $id) = $attributesArray;
                            if (!$model = MembersAttributesAnswers::findOne(['attribute_id' => $id, 'member_id' => Yii::$app->getUser()->id])) {
                                $model = new MembersAttributesAnswers();
                            }

                            $has_option = false;

                            if ($questionsModel = MembersAttributes::findOne($id)) {
                                $type_id = $questionsModel->type_id;
                                $has_option = FieldsTypes::findOne($type_id)->has_options;
                                $filedTypeTitle = FieldsTypes::findOne($type_id)->title;
                            }

                            if ($has_option) {
                                $model->value = '';
                                $model->option_id = '';
                                if ($optionsModel = MembersAttributesToOptions::findOne($attributesAnswers->{$attribute_id})) {
                                    $model->value = $optionsModel->title;
                                    $model->option_id = $optionsModel->id;
                                }
                            } else {
                                $model->value = $attributesAnswers->{$attribute_id};
                                if (isset($filedTypeTitle) && $filedTypeTitle == 'Date Picker') { //@TODO replace datepicker with some database constatnt value;
                                    $model->value = strtotime($attributesAnswers->{$attribute_id});
                                }
                            }

                            $model->attribute_id = $id;
                            $model->member_id = Yii::$app->getUser()->id;

                            $model->save();
                        }
                    }
                    $mappingAttributesSaved = true;
                }

                if ($mappingQuestionsSaved && $mappingAttributesSaved) {
                    $user = Yii::$app->getUser()->getIdentity();
                    $user->status = User::STATUS_ACTIVE;

                    if ($user->save()) {
                        return Yii::$app->response->redirect(array('members'));
                    } else {
                        Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not save mapping data. Please contact system administrator'));
                    }

                } else {
                    $categoryModel = MappingCategories::findOne(['id' => $pageModel->category_id]);

                    $attributesModel = MembersAttributes::find(['id in ' => $categoryModel->attributes_id])->with('fieldsTypes')->all();;
                    $questionsModel = MappingQuestions::find(['category_id' => $categoryModel->id])->with('fieldsTypes')->all();

                    return $this->render('mapping', [
                        'questionsAnswers' => $questionsAnswers,
                        'attributesAnswers' => $attributesAnswers,
                        'pageModel' => $pageModel,
                        'categoryModel' => $categoryModel,
                        'questionsModel' => $questionsModel,
                        'attributesModel' => $attributesModel,
                    ]);
                }
            } else {

                return $this->render('mapping', [
                    'questionsAnswers' => $questionsAnswers,
                    'attributesAnswers' => $attributesAnswers,
                    'pageModel' => $pageModel,
                    'categoryModel' => $categoryModel,
                    'questionsModel' => $questionsModel,
                    'attributesModel' => $attributesModel,
                ]);
            }
        } else {
            return Yii::$app->response->redirect(array('site/login'));
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
