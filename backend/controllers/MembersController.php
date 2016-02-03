<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Members;
use backend\models\GeneralSettings;
use backend\models\CmsPages;
use backend\models\MappingCategories;
use frontend\models\MembersWeightTracker;

/**
 * MembersController implements the CRUD actions for Members model.
 */
class MembersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Members models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Members::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Members model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // get mapping category
        $pageModel = CmsPages::findOne(['id' => GeneralSettings::findOne(['name' => 'mapping_page_id'])->value]);

        //$categoryModel = MappingCategories::findOne($pageModel->mapping_id);
        $categoriesModel = MappingCategories::findAll(['is_active' => 1]);

        // get questions answers
        /*$questionsAnswers = new ActiveDataProvider([
            'query' => $categoryModel->getQuestions()->with('questionsAnswers'),
        ]);*/

        // get attributes Answers
        /*$attributesAnswers = new ActiveDataProvider([
            'query' => MembersAttributes::find($categoryModel->attributes_id)->with('attributesAnswers'),
        ]);*/

        $weightTracker = new ActiveDataProvider([
            'query' => MembersWeightTracker::find(['member_id' => Yii::$app->getUser()->id]),
            'sort' => ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            //'questionsAnswers' => $questionsAnswers,
            'categoriesModel' => $categoriesModel,
            //'attributesAnswers' => $attributesAnswers,
            'weightTracker' => $weightTracker,
        ]);
    }

    /**
     * Creates a new Members model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Members();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Members model.
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
     * Deletes an existing Members model.
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
     * Finds the Members model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Members the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Members::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
