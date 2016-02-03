<?php

namespace backend\controllers;

use backend\models\MappingCategoriesSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Lang;
use backend\models\MappingCategories;
use backend\models\MappingQuestions;
use backend\models\MappingQuestionsToOptions;

/**
 * MappingCategoriesController implements the CRUD actions for MappingCategories model.
 */
class MappingCategoriesController extends Controller
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
     * Lists all MappingCategories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MappingCategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single MappingCategories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $categories = $this->findModel($id);
        $questions = $categories->getQuestions();

        $dataProvider = new ActiveDataProvider([
            'query' => $questions,
            'sort' => ['defaultOrder' => ['order'=>SORT_DESC]],
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'category' => $categories
        ]);
    }

    /**
     * Creates a new MappingCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MappingCategories();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
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
     * Updates an existing MappingCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
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
     * Deletes an existing MappingCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        foreach (MappingQuestions::find()->where(['category_id' => $id])->all() as $question) {
            MappingQuestionsToOptions::deleteAll(['question_id' => $question->id]);
            $question->delete();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MappingCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MappingCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MappingCategories::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
