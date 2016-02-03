<?php

namespace backend\controllers;

use backend\models\MappingQuestionsToOptions;
use backend\models\FieldsTypes;
use backend\models\Lang;
use Yii;
use backend\models\MappingQuestions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MappingQuestionsController implements the CRUD actions for MappingQuestions model.
 */
class MappingQuestionsController extends Controller
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
                    'get-options' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MappingQuestions models.
     * @return mixed
     */
    public function actionIndex()
    {
        return Yii::$app->response->redirect(array('mapping-categories/index','id'=>302));
    }

    /**
     * Displays a single MappingQuestions model.
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
     * Creates a new MappingQuestions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id - category id this question belongs to
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new MappingQuestions();
        $optionsDataProvider = null;
        $optionsModel = new MappingQuestionsToOptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            return $this->redirect(['mapping-questions/update', 'id' => $model->id, 'cat_id' => $model->category_id ]);
        } else {

            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            return $this->render('create', [
                'model' => $model,
                'category_id' => $id,
                'optionsDataProvider' => $optionsDataProvider,
                'optionsModel' => $optionsModel,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }

    /**
     * Updates an existing MappingQuestions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $cat_id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['mapping-categories/view', 'id' => $cat_id]);
            Yii::$app->getSession()->setFlash('success', 'Successfully updated.');
            return $this->redirect(['mapping-questions/update', 'id' => $model->id, 'cat_id' => $model->category_id ]);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            $typeModel = FieldsTypes::findOne($model->type_id);

            $optionsDataProvider = null;

            if ($typeModel->has_options) {
                $optionsDataProvider = $this->_getQuestionOptions($id);
            }

            $optionsModel = new MappingQuestionsToOptions();

            return $this->render('update', [
                'model' => $model,
                'category_id' => $cat_id,
                'optionsDataProvider' => $optionsDataProvider,
                'optionsModel' => $optionsModel,
                'languages' => $languages,
                'languageDefault' => $languageDefault
            ]);
        }
    }

    /**
     * Deletes an existing MappingQuestions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $cat_id)
    {
        $this->findModel($id)->delete();
        MappingQuestionsToOptions::deleteAll(['question_id' => $id]);

        return $this->redirect(['mapping-categories/view', 'id' => $cat_id]);
    }

    public function actionGetOptions() {
        $request = Yii::$app->request;

        $type_id = (int)$request->post('type_id');
        $typeModel = FieldsTypes::findOne($type_id);

        if ($typeModel->has_options) {
            $question_id = (int)$request->post('question_id');

            $dataProvider = $this->_getQuestionOptions($question_id);

            if ($dataProvider) {
                if (Yii::$app->request->isAjax) {
                    $options_html  = $this->renderPartial('options', [
                        'dataProvider' => $dataProvider,
                    ]);

                    $response = ['status' => 'success', 'options' => $options_html, 'other_field' => $typeModel->has_other_field];
                    \Yii::$app->response->format = 'json';

                    return $response;
                } else {
                    return false;
                }
            }
        } else {
            if (Yii::$app->request->isAjax) {
                $response = ['status' => 'success', 'options' => null, 'other_field' => $typeModel->has_other_field];
                \Yii::$app->response->format = 'json';

                return $response;
            } else {
                return false;
            }
        }
    }

    private function _getQuestionOptions ($id) {

         if (empty($id)) {
             return false;
         } else {
             $questions = $this->findModel($id);

             if ($questions!= null) {
                 $options = $questions->getOptions();

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
     * Finds the MappingQuestions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MappingQuestions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MappingQuestions::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
