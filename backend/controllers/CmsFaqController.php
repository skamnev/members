<?php

namespace backend\controllers;

use backend\models\CmsFaqCategories;
use backend\models\SearchCmsFaq;
use Yii;
use backend\models\CmsFaq;
use backend\models\Lang;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CmsFaqController implements the CRUD actions for CmsFaq model.
 */
class CmsFaqController extends Controller
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
     * Lists all CmsFaq models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $searchModel = new SearchCmsFaq();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }*/

    public function actionPreview ($id, $category_id)
    {
        $model = $this->findModel($id);
        $category = CmsFaqCategories::findOne(['id' => $category_id]);

        $this->layout = 'preview';
        return $this->render('@frontend/views/faq/view', [
            'model' => $model,
            'category' => $category
        ]);
    }
    /**
     * Creates a new CmsFaq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category_id = null)
    {
        $model = new CmsFaq();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully saved.');
            if ($category_id) {
                return $this->redirect(['cms-faq/update', 'id' => $model->id, 'category_id' => $category_id ]);
            } else {
                $category_id = preg_split('/[\[\],]/i', $model->category_id, -1, PREG_SPLIT_NO_EMPTY)[0];
                return $this->redirect(['cms-faq/update', 'id' => $model->id, 'category_id' => $category_id ]);
            }
        } else {
            $languages = Lang::find()->all();
            $languageDefault = Lang::findOne(['default' => 1]);

            $categoryModel = new CmsFaqCategories;
            
            if ($category_id) {
                $categoryModel = CmsFaqCategories::findOne($category_id);
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
     * Updates an existing CmsFaq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $category_id)
    {
        $model = $this->findModel($id);
        $categoryModel = CmsFaqCategories::findOne($category_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Successfully updated.');
            return $this->redirect(['cms-faq/update', 'id' => $model->id, 'category_id' => $category_id ]);
        }
        
        $languages = Lang::find()->all();
        $languageDefault = Lang::findOne(['default' => 1]);

        return $this->render('update', [
            'model' => $model,
            'languages' => $languages,
            'languageDefault' => $languageDefault,
            'categoryModel' => $categoryModel,
        ]);
    }

    /**
     * Deletes an existing CmsFaq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $category_id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['cms-faq-categories/view', 'category_id' => $category_id]);
    }

    /**
     * Finds the CmsFaq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsFaq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsFaq::find()->where(['id' => $id])->multilingual()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
