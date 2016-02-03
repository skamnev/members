<?php

namespace backend\controllers;

use backend\models\MappingCategories;
use backend\models\MappingQuestions;
use backend\models\MappingQuestionsToOptions;
use Yii;
use backend\models\PdfsRules;
use backend\models\PdfsRulesSearch;
use backend\models\Lang;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PdfsRulesController implements the CRUD actions for PdfsRules model.
 */
class PdfsRulesController extends Controller
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
     * Lists all PdfsRules models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PdfsRulesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PdfsRules model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGetQuestionsList() {
        $id = Yii::$app->request->post('id',null);
        $optionsHtml = '';

        if (!empty($id) && $id>0) {
            $questions = MappingQuestions::find()->joinWith('fieldsTypes')
                ->where(['category_id'=>$id, 'fields_type.has_options' => 1]);

            if($questions->count()>0) {

                $questionsList = $questions->all();

                $optionsHtml .= '<option>' . Yii::t('backend', 'Select Question') . '</option>';
                foreach($questionsList as $question) {
                    $optionsHtml .= "<option value='" . $question->id . "'>" . $question->title . "</option>";
                }

            }else {
                $optionsHtml .= "<option> - </option>";
            }
        }

        echo $optionsHtml;
    }

    public function actionGetOptionsList() {
        $id = Yii::$app->request->post('id',null);
        $optionsHtml = '';

        if (!empty($id) && $id>0) {
            $options = MappingQuestionsToOptions::find()->where(['question_id'=>$id]);

            if($options->count()>0) {

                $optionsList = $options->all();

                $optionsHtml .= '<option>' . Yii::t('backend', 'Select Options') . '</option>';
                foreach($optionsList as $option) {
                    $optionsHtml .= "<option value='" . $option->id . "'>" . $option->title . "</option>";
                }


            }else {
                $optionsHtml .= "<option> - </option>";
            }
        }

        echo $optionsHtml;
    }

    /**
     * Creates a new PdfsRules model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pdf_id)
    {
        $model = new PdfsRules();

        $model->pdf_id = $pdf_id;

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
                $mappingCategories = new MappingCategories();

                return $this->render('create', [
                    'model' => $model,
                    'languages' => $languages,
                    'languageDefault' => $languageDefault,
                    'mappingCategories' => $mappingCategories,
                ]);
            }
        }
    }

    /**
     * Updates an existing PdfsRules model.
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

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $model,
                    'languages' => $languages,
                    'languageDefault' => $languageDefault
                ]);
            } else {

                return $this->render('create', [
                    'model' => $model,
                    'languages' => $languages,
                    'languageDefault' => $languageDefault
                ]);
            }
        }
    }

    /**
     * Deletes an existing PdfsRules model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $rule = $this->findModel($id);
        $redirect = ['pdfs/update', 'id' => $rule->pdf_id];

        $rule->delete();

        return $this->redirect($redirect);
    }

    /**
     * Finds the PdfsRules model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PdfsRules the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PdfsRules::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
