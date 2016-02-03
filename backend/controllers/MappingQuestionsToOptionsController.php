<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\MappingQuestionsToOptions;
use backend\models\MappingQuestions;
use backend\models\Lang;

/**
 * MappingQuestionsToOptionsController implements the CRUD actions for MappingQuestionsToOptions model.
 */
class MappingQuestionsToOptionsController extends Controller
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
     * Lists all MappingQuestionsToOptions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MappingQuestionsToOptions::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MappingQuestionsToOptions model.
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
     * Creates a new MappingQuestionsToOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //\Yii::$app->response->format = 'json';
        //return Yii::$app->request->post();
        $model = new MappingQuestionsToOptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                $response = ['status' => 'success', 'message' => 'Data was successfully saved'];
                \Yii::$app->response->format = 'json';

                return $response;
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                $response = ['status' => 'failed', 'message' => 'Failed to save data'];
                \Yii::$app->response->format = 'json';

                return $response;
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
    }

    /**
     * Updates an existing MappingQuestionsToOptions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                $response = ['status' => 'success', 'message' => 'Data was successfully saved'];
                \Yii::$app->response->format = 'json';

                return $response;
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            return $this->renderAjax('update', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }

    /**
     * Deletes an existing MappingQuestionsToOptions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $option = $this->findModel($id);
        $question = MappingQuestions::findOne($option->question_id);

        $this->findModel($id)->delete();

        return $this->redirect(['mapping-questions/update', 'cat_id' => $question->category_id, 'id' => $question->id]);
    }

    /**
     * Finds the MappingQuestionsToOptions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MappingQuestionsToOptions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MappingQuestionsToOptions::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
