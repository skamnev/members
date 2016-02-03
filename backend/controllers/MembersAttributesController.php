<?php

namespace backend\controllers;

use Yii;
use backend\models\MembersAttributesToOptions;
use backend\models\MembersAttributes;
use backend\models\FieldsTypes;
use backend\models\Lang;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MembersAttributesController implements the CRUD actions for MembersAttributes model.
 */
class MembersAttributesController extends Controller
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
     * Lists all MembersAttributes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MembersAttributes::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MembersAttributes model.
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
     * Creates a new MembersAttributes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MembersAttributes();
        $optionsDataProvider = null;
        $optionsModel = new MembersAttributesToOptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            return $this->redirect(['members-attributes/update', 'id' => $model->id]);
        } else {

            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            return $this->render('create', [
                'model' => $model,
                'optionsDataProvider' => $optionsDataProvider,
                'optionsModel' => $optionsModel,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }

    /**
     * Updates an existing MembersAttributes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully updated.');
            return $this->redirect(['members-attributes/update', 'id' => $model->id]);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            $typeModel = FieldsTypes::findOne($model->type_id);

            $optionsDataProvider = null;

            if ($typeModel->has_options) {
                $optionsDataProvider = $this->_getAttributesOptions($id);
            }

            $optionsModel = new MembersAttributesToOptions();

            return $this->render('update', [
                'model' => $model,
                'optionsDataProvider' => $optionsDataProvider,
                'optionsModel' => $optionsModel,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }


    /**
     * Deletes an existing MembersAttributes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        MembersAttributesToOptions::deleteAll(['attribute_id' => $id]);

        return $this->redirect(['index']);
    }

    public function actionGetOptions() {
        $request = Yii::$app->request;

        $type_id = (int)$request->post('type_id');
        $typeModel = FieldsTypes::findOne($type_id);

        if ($typeModel->has_options) {
            $attribute_id = (int)$request->post('attribute_id');

            $dataProvider = $this->_getAttributesOptions($attribute_id);

            if ($dataProvider) {
                return  $this->renderAjax('options', [
                    'dataProvider' => $dataProvider,
                ]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                $response = ['status' => 'success', 'options' => null];
                \Yii::$app->response->format = 'json';

                return $response;
            } else {
                return false;
            }
        }
    }

    private function _getAttributesOptions ($id) {

        if (empty($id)) {
            return false;
        } else {
            $attributes = $this->findModel($id);

            if ($attributes!= null) {
                $options = $attributes->getOptions();

                $dataProvider = new ActiveDataProvider([
                    'query' => $options,
                ]);

                return $dataProvider;
            }
        }

        return false;
    }

    private function _hasTypeOptions ($type_id) {
        $typeModel = FieldsTypes::findOne($type_id);

        if ($typeModel->has_options) {
            return true;
        }

        return false;
    }

    /**
     * Finds the MembersAttributes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MembersAttributes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MembersAttributes::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
