<?php

namespace backend\controllers;

use backend\models\PdfsRules;
use backend\models\MappingCategories;
use Yii;
use backend\models\Pdfs;
use backend\models\PdfsSearch;
use backend\models\Lang;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * PdfsController implements the CRUD actions for Pdfs model.
 */
class PdfsController extends Controller
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
     * Lists all Pdfs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PdfsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pdfs model.
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
     * Creates a new Pdfs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pdfs();
        $rulesModel = new PdfsRules();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            $rulesDataProvider = null;
            $mappingCategories = new MappingCategories();
            
            return $this->render('create', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault,
                'rulesDataProvider' => $rulesDataProvider,
                'rulesModel' => $rulesModel,
                'mappingCategories' => $mappingCategories,
            ]);
        }
    }

    /**
     * Updates an existing Pdfs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully updated.');
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            $rulesModel = new PdfsRules();

            $rules = $model->getPdfsRules()->joinWith('pdfQuestions');

            $rulesDataProvider = new ActiveDataProvider([
                'query' => $rules,
            ]);
            $mappingCategories = new MappingCategories();

            return $this->render('update', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault,
                'rulesDataProvider' => $rulesDataProvider,
                'rulesModel' => $rulesModel,
                'mappingCategories' => $mappingCategories,
            ]);
        }
    }

    public function actionGetRules() {
        $request = Yii::$app->request;

        $pdf_id = (int)$request->post('pdf_id');

        if ($pdf_id) {
            $model = $this->findModel($pdf_id);

            $rulesModel = new PdfsRules();

            $rules = $model->getPdfsRules();

            $rulesDataProvider = new ActiveDataProvider([
                'query' => $rules,
            ]);

            if ($rulesDataProvider) {
                return  $this->renderPartial('rules', [
                    'rulesDataProvider' => $rulesDataProvider,
                ]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                $response = ['status' => 'success', 'rules' => null];
                \Yii::$app->response->format = 'json';

                return $response;
            } else {
                return false;
            }
        }
    }

    /**
     * Deletes an existing Pdfs model.
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
     * Finds the Pdfs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pdfs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pdfs::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
