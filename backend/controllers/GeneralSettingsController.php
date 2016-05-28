<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\GeneralSettings;

/**
 * GeneralSettingsController implements the CRUD actions for GeneralSettings model.
 */
class GeneralSettingsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all GeneralSettings models.
     * @return mixed
     */
    public function actionWeightTracker()
    {
        $model = $this->findModel(['name' => 'weight_tracker_frequence']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            return $this->redirect(['weight-tracker']);
        } else {
            return $this->render('weight_tracker/weight-tracker', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Lists all GeneralSettings models.
     * @return mixed
     */
    public function actionMealPlan()
    {
        $model = $this->findModel(['name' => 'current_mealplan_id']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            return $this->redirect(['meal-plan']);
        } else {
            return $this->render('mealplan/mealplan', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Lists all GeneralSettings models.
     * @return mixed
     */
    public function actionMappingPage()
    {
        $model = $this->findModel(['name' => 'mapping_page_id']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mapping/mapping-page']);
        } else {
            return $this->render('mapping/mapping-page', [
                'model' => $model,
            ]);
        }


    }

    /**
     * Lists all GeneralSettings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel(['name' => 'pdfs_availability_delay']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            //return $this->redirect(['general-settings/index']);
        } else {
        }
        
        return $this->render('general/index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single GeneralSettings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GeneralSettings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GeneralSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GeneralSettings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GeneralSettings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GeneralSettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GeneralSettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GeneralSettings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
