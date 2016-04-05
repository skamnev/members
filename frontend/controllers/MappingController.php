<?php
namespace frontend\controllers;

use backend\models\FieldsTypes;
use backend\models\GeneralSettings;
use backend\models\CmsPages;
use backend\models\MappingCategories;
use backend\models\MappingQuestions;
use backend\models\MappingQuestionsToOptions;
//use backend\models\MembersAttributes;
//use frontend\models\MembersAttributesAnswers;
//use backend\models\MembersAttributesToOptions;
use frontend\models\MembersProgress;
use frontend\models\MembersQuestionsAnswers;
//use backend\models\MembersMappingAnswers;
//use frontend\components\MappingValidator;
use frontend\models\User;
//use frontend\components\BraintreeForm;
use Yii;
//use frontend\models\User;
//use frontend\models\LoginForm;
//use frontend\models\PasswordResetRequestForm;
//use frontend\models\ResetPasswordForm;
//use frontend\models\SignupForm;
//use frontend\models\ContactForm;
//use yii\base\InvalidParamException;
//use yii\web\BadRequestHttpException;
//use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use yii\helpers\BaseVarDumper;
//use yii\data\ActiveDataProvider;


/**
 * Site controller
 */
class MappingController extends MainController
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
                        'actions' => ['logout', 'backend', 'pay'],
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

    public function actionUpdateAnswer ($id) {
        $btnYes = Yii::$app->request->post('btn-yes');
        $btnNo = Yii::$app->request->post('btn-no');

        if (isset($btnNo)) {
            $memberProgress = MembersProgress::find()->where(['member_id' => Yii::$app->user->id, 'category_id' => $id])->one();

            if (!$memberProgress) {
                $memberProgress = new MembersProgress();

                $memberProgress->progress = 1;
                $memberProgress->member_id = Yii::$app->user->id;
                $memberProgress->category_id = $id;
            } else {
                $memberProgress->progress++;
            }

            if ($memberProgress->save()) {
                return Yii::$app->response->redirect(array('members/index'));
            }
        } else {

            $questionsAnswers = new MembersQuestionsAnswers($id);

            $precessStatus = Yii::$app->MappingComponent->processQuestions($questionsAnswers);

            if ($precessStatus) {
                return Yii::$app->response->redirect(array('members/index'));
            } else {
                $categoryModel = MappingCategories::findOne($id);
                $questionsModel = MappingQuestions::find()
                    ->where(['category_id' => $id, 'is_active' => 1])
                    ->orderBy('order DESC')
                    ->with('fieldsTypes')
                    ->all();

                return $this->render('update_answers', [
                    'categoryModel' => $categoryModel,
                    'questionsModel' => $questionsModel,
                    'questionsAnswers' => $questionsAnswers,
                ]);
            }
        }
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {

            Yii::$app->MappingComponent->process();

            $steps = Yii::$app->MappingComponent->getSteps();

            if (empty($steps['view'])) {
                $user = Yii::$app->getUser()->getIdentity();
                $user->status = User::STATUS_ACTIVE;

                if ($user->save()) {
                    return Yii::$app->response->redirect(array('members/index'));
                } else {
                    Yii::$app->getSession()->setFlash('warning', Yii::t('errors', 'Can not update user status. Please contact system administrator'));
                }
            }

            $settingsModel = GeneralSettings::findOne(['name' => 'mapping_page_id']);
            
            //$pageModel = CmsPages::findOne(['id' => $settingsModel->value]);

            return $this->render('mapping', [
                'steps' => $steps,
                //'pageModel' => $pageModel,
            ]);

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
            if (Yii::$app->request->post()) {
                $mappingQuestionsSaved = false;
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
            $categoryModel = MappingCategories::find()->where(['is_active' => 1])->orderBy('sort_order')->all();
            $questionsModel = \backend\models\MappingQuestions::find()->where(['category_id' => $category->id])->with('fieldsTypes')->all();
            //$questionsModel = MappingQuestions::find()->where(['category_id' => $categoryModel->id])->with('fieldsTypes')->all();

            return $this->render('mapping', [
                'questionsAnswers' => $questionsAnswers,
                'pageModel' => $pageModel,
                'categoryModel' => $categoryModel,
            ]);

        } else {
            return Yii::$app->response->redirect(array('site/login'));
        }
    }
}