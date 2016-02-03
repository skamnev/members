<?php

namespace backend\controllers;

use backend\models\SearchCmsPagesCategories;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\CmsPagesCategories;
use backend\models\Lang;
use backend\models\SearchCmsPages;
use backend\models\CmsPages;

/**
 * CmsPagesCategoriesController implements the CRUD actions for CmsPagesCategories model.
 */
class CmsPagesCategoriesController extends Controller
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
     * Lists all CmsPagesCategories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCmsPagesCategories();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CmsPagesCategories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($category_id)
    {
        $searchModel = new SearchCmsPages(['category_id'=>$category_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'category_id' => $category_id
        ]);
    }

    /**
     * Creates a new CmsPagesCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CmsPagesCategories();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            return $this->render('create', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }

    /**
     * Updates an existing CmsPagesCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            return $this->render('update', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }

    /**
     * Deletes an existing CmsPagesCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        CmsPages::deleteAll(['category_id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the CmsPagesCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsPagesCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsPagesCategories::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
