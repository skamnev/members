<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use backend\models\CmsFaqCategories;
use backend\models\MappingQuestionsCodes;
use backend\models\GeneralSettings;
use frontend\components\MembersCodesComponent;
use frontend\models\MembersAttributesAnswers;
use frontend\models\MembersQuestionsAnswers;
use frontend\models\CmsFaq;
use frontend\models\SearchCmsFaq;

/**
 * FaqController implements the CRUD actions for CmsFaq model.
 */
class FaqController extends MainController
{
    /**
     * @inheritdoc
     */
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionCategories()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CmsFaqCategories::find()->where(['status' => 1]),
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC]],
        ]);

        return $this->render('category/view', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategory($id) {
        /**
         * Define default member group code
         */
        $faqQuery = \backend\models\CmsFaq::find()->where(['is_active' => 1]);
        $faqQuery->andWhere(['like', 'category_id',"[$id]"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $faqQuery,
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC]]
        ]);

        $category = CmsFaqCategories::find($id)->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category
        ]);
    }

    /**
     * Displays a single CmsFaq model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($category_id, $id)
    {
        $model = $this->findModel($id);
        $category = CmsFaqCategories::findOne(['id' => $category_id]);

        return $this->render('search/view', [
            'model' => $model,
            'category' => $category
        ]);
    }
    
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
    
    public function actionSearch()
    {
        $searchParam = Yii::$app->request->get('DynamicModel')['search_text'];
        
        $faqQuery = \backend\models\CmsFaq::find()->where(['is_active' => 1])
            ->andFilterWhere(['like', 'cms_faq_lang.title', $searchParam])
            ->orFilterWhere(['like', 'cms_faq_lang.content', $searchParam]);

        $faqQuery->joinWith(['cmsFaqLangs' => function ($query) {
            $query->where(['language' => Yii::$app->language]);
        }]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $faqQuery,
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC, 'updated_at'=>SORT_ASC]]
        ]);
        
        return $this->render('search/index', [
            'dataProvider' => $dataProvider,
        ]);
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
        if (($model = \backend\models\CmsFaq::find()->where(['is_active' => 1])->andFilterWhere(['or', ['id' => $id],[ 'identifier' => $id]])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
