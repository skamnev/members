<?php

namespace backend\controllers;

use backend\models\CmsPagesCategories;
use backend\models\SearchCmsPages;
use Yii;
use backend\models\CmsPages;
use backend\models\Lang;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CmsPagesController implements the CRUD actions for CmsPages model.
 */
class CmsPagesController extends Controller
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
     * Lists all CmsPages models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $searchModel = new SearchCmsPages();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }*/

    public function actionPreview ($id, $category_id)
    {
        $model = $this->findModel($id);
        $category = CmsPagesCategories::findOne(['id' => $category_id]);

        $this->layout = 'preview';
        return $this->render('@frontend/views/articles/view', [
            'model' => $model,
            'category' => $category
        ]);
    }
    /**
     * Creates a new CmsPages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category_id = null)
    {
        $model = new CmsPages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($category_id) {
                return $this->redirect(['cms-pages-categories/view', 'category_id' => $category_id]);
            } else {
                return $this->redirect(['cms-pages-categories/index']);
            }
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            $categoryModel = new CmsPagesCategories;
            if ($category_id) {
                $categoryModel = CmsPagesCategories::findOne($category_id);
            }

            return $this->render('create', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault,
                'categoryModel' => $categoryModel,
            ]);
        }
    }

    /**
     * Updates an existing CmsPages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $category_id)
    {
        $model = $this->findModel($id);
        $categoryModel = CmsPagesCategories::findOne($category_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cms-pages-categories/view', 'category_id' => $category_id]);
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            return $this->render('update', [
                'model' => $model,
                'languages' => $languages,
                'languageDefault' => $languageDefault,
                'categoryModel' => $categoryModel,
            ]);
        }
    }

    /**
     * Deletes an existing CmsPages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $category_id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['cms-pages-categories/view', 'category_id' => $category_id]);
    }

    /**
     * Finds the CmsPages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsPages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsPages::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
