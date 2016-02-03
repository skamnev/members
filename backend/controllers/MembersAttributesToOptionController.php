<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\MembersAttributesToOptions;
use backend\models\Lang;
use yii\filters\AccessControl;

/**
 * MembersAttributesToOptionController implements the CRUD actions for MembersAttributesToOptions model.
 */
class MembersAttributesToOptionController extends Controller
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
     * Lists all MembersAttributesToOptions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MembersAttributesToOptions::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new MembersAttributesToOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MembersAttributesToOptions();

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
     * Updates an existing MembersAttributesToOptions model.
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
     * Deletes an existing MembersAttributesToOptions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$option = $this->findModel($id);
        //$question = MembersAttributes::findOne($option->attribute_id);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MembersAttributesToOptions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MembersAttributesToOptions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MembersAttributesToOptions::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
