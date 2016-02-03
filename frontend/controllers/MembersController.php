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
                'only' => ['logout', 'signup', 'backend', 'weight-tracker', 'pdfs'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'backend', 'weight-tracker', 'pdfs'],
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

    /**
     * Displays weight tracker page.
     *
     * @return mixed
     */
    public function actionWeightTracker()
    {
        $settingsModel = GeneralSettings::findOne(['name' => 'weight_tracker_frequence']);
        $userModel = Yii::$app->user->getIdentity();

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
        ]);
    }


    /**
     * Creates a new MembersWeightTracker model.
     * If creation is successful, the browser will be redirected to the members 'weight tracker' page.
     * @return mixed
     */
    public function actionWeightTrackerAdd()
    {
        $weightTracker = MembersWeightTracker::find(['member_id' => Yii::$app->getUser()->id])->orderBy(['created_at'=>SORT_DESC])->one();

        if ((time() - strtotime($weightTracker->created_at))<(MembersWeightTracker::UPDATES_FREQUENCY*3600*24)) {
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