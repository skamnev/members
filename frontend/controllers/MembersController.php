<?php

namespace frontend\controllers;

use frontend\models\MembersAttributesAnswers;
use Yii;
use frontend\models\Lang;
use frontend\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\filters\AccessControl;
use frontend\models\MembersWeightTracker;
use backend\models\GeneralSettings;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class MembersController extends MainController {
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'backend', 'meal-plan', 'weight-tracker', 'pdfs'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'backend', 'meal-plan', 'weight-tracker', 'pdfs'],
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
     * Displays index page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPdfs() {
        if (!Yii::$app->user->isGuest) {
            $pdfs_listing = Yii::$app->PdfsComponent->getAvailablePDFs(Yii::$app->user->getIdentity());
        }

        return $this->render('pdfs/index', ['pdfs_listing' => $pdfs_listing]);
    }

    public function actionPdfDownload($id) {
        if (!Yii::$app->user->isGuest) {
            $pdf = Yii::$app->PdfsComponent->getAvailablePdfById(Yii::$app->user->getIdentity(),$id);
            
            if ($pdf) {
                $file_path = $pdf->getUploadedFilePath('file');

                if (file_exists($file_path)) {
                    $file_path = $pdf->getUploadedFilePath('file');

                    $full_name = Yii::$app->user->identity->firstname . ' ' . Yii::$app->user->identity->lastname;
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . $full_name . '.pdf"');

                    readfile($file_path);
                }
            }
        }
    }

    /**
     * Displays weight tracker page.
     *
     * @return mixed
     */
    public function actionMealPlan()
    {
        $userModel = Yii::$app->user->getIdentity();
        
        if ($userModel->load(Yii::$app->request->post()) && $userModel->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully updated.');
        } else {
            
        }
        
        $settingsModel = GeneralSettings::findOne(['name' => 'current_mealplan_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => MembersWeightTracker::find()->where(['member_id' => Yii::$app->getUser()->id]),
            'sort' => ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $lastCreateDate = 0;
        $currentWeight = 0;
        if ($dataProvider->count > 0) {
            $lastCreateDate = $dataProvider->getModels()[0]->created_at;
            $currentWeight = $dataProvider->getModels()[0]->value;
        }

        $allowAddWeight = false;

        if ((time() - strtotime($lastCreateDate))>($settingsModel->value*3600*24)) {
            $allowAddWeight = true;
        }

        return $this->render('weight_tracker', [
            'dataProvider' => $dataProvider,
            'startingWeight' => empty($userModel->weight)?'':$userModel->weight,
            'currentWeight' => $currentWeight,
            'allowAddWeight' => $allowAddWeight,
            'userModel' => $userModel,
        ]);
    }
    
    /**
     * Displays weight tracker page.
     *
     * @return mixed
     */
    public function actionWeightTracker()
    {
        $userModel = Yii::$app->user->getIdentity();
        
        if ($userModel->load(Yii::$app->request->post()) && $userModel->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully updated.');
        } else {
            
        }
        
        $settingsModel = GeneralSettings::findOne(['name' => 'weight_tracker_frequence']);

        $dataProvider = new ActiveDataProvider([
            'query' => MembersWeightTracker::find()->where(['member_id' => Yii::$app->getUser()->id]),
            'sort' => ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $lastCreateDate = 0;
        $currentWeight = 0;
        if ($dataProvider->count > 0) {
            $lastCreateDate = $dataProvider->getModels()[0]->created_at;
            $currentWeight = $dataProvider->getModels()[0]->value;
        }

        $allowAddWeight = false;

        if ((time() - strtotime($lastCreateDate))>($settingsModel->value*3600*24)) {
            $allowAddWeight = true;
        }

        return $this->render('weight_tracker', [
            'dataProvider' => $dataProvider,
            'startingWeight' => empty($userModel->weight)?'':$userModel->weight,
            'currentWeight' => $currentWeight,
            'allowAddWeight' => $allowAddWeight,
            'userModel' => $userModel,
        ]);
    }


    /**
     * Creates a new MembersWeightTracker model.
     * If creation is successful, the browser will be redirected to the members 'weight tracker' page.
     * @return mixed
     */
    public function actionWeightTrackerAdd()
    {
        $settingsModel = GeneralSettings::findOne(['name' => 'weight_tracker_frequence']);
        $weightTracker = MembersWeightTracker::find(['member_id' => Yii::$app->getUser()->id])->orderBy(['created_at'=>SORT_DESC])->one();

        if ((time() - strtotime($weightTracker->created_at))<($settingsModel->value*3600*24)) {
            return $this->redirect(['weight-tracker']);
        }

        $model = new MembersWeightTracker();

        if ($model->load(Yii::$app->request->post())) {
            $model->member_id = Yii::$app->getUser()->id;

            if ($model->save()) {
                return $this->redirect(['weight-tracker']);
            }
        } else {
            return $this->render('weight_tracker_add', [
                'model' => $model,
            ]);
        }
    }
}