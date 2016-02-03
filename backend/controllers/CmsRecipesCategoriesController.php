<?php

namespace backend\controllers;

use backend\models\SearchCmsRecipesCategories;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\SearchCmsRecipes;
use backend\models\CmsRecipesCategories;
use backend\models\Lang;

/**
 * CmsRecipesCategoriesController implements the CRUD actions for CmsRecipesCategories model.
 */
class CmsRecipesCategoriesController extends Controller
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
     * Lists all CmsRecipesCategories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCmsRecipesCategories();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CmsRecipesCategories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($category_id)
    {
        $searchModel = new SearchCmsRecipes(['category_id'=>$category_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'category_id' => $category_id,
        ]);
    }

    /**
     * Creates a new CmsRecipesCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CmsRecipesCategories();

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
     * Updates an existing CmsRecipesCategories model.
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
     * Deletes an existing CmsRecipesCategories model.
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
     * Finds the CmsRecipesCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsRecipesCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsRecipesCategories::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
