<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use backend\models\CmsPagesCategories;
use backend\models\MappingQuestionsCodes;
use backend\models\GeneralSettings;
use frontend\components\MembersCodesComponent;
use frontend\models\MembersAttributesAnswers;
use frontend\models\MembersQuestionsAnswers;
use frontend\models\CmsPages;

/**
 * ArticlesController implements the CRUD actions for CmsPages model.
 */
class ArticlesController extends MainController
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
            'query' => CmsPagesCategories::find()->where(['status' => 1]),
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

        //get code id with max sum value
        $memberGroupCode = MembersCodesComponent::getMemberGroupCode();
        $memberAnswersCodes = MembersCodesComponent::getMemberCodesSqlFilter();

        $pagesQuery = CmsPages::find()->where(['is_active' => 1]);

        if (!empty($memberGroupCode)) {
            //add default group to exclude codes array
            $memberAnswersCodes['exclude'][] = ['not like', 'no_code_id', "[$memberGroupCode]"];
            //add default group to include codes array
            $memberAnswersCodes['include'][] = ['like', 'code_id', "[$memberGroupCode]"];
            
            //add conditions to the where instance if exclude codes array not empty
            if (count($memberAnswersCodes['exclude'])>0) {
                $pagesQuery->andWhere(array_merge(['and'],$memberAnswersCodes['exclude']))
                    ->orWhere(['no_code_id' => NULL]);
            }
            //add conditions to the where instance if include codes array not empty
            if (count($memberAnswersCodes['include'])>0) {
                $pagesQuery->andWhere(array_merge(['or'],$memberAnswersCodes['include']))
                    ->orWhere(['code_id' => '']);
            }
        }

        $pagesQuery->andWhere(['like', 'category_id',"[$id]"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $pagesQuery,
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC]]
        ]);

        $category = CmsPagesCategories::find($id)->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category
        ]);
    }

    /**
     * Displays a single CmsPages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($category_id, $id)
    {
        $model = $this->findModel($id);
        $category = CmsPagesCategories::findOne(['id' => $category_id]);

        return $this->render('view', [
            'model' => $model,
            'category' => $category
        ]);
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
        if (($model = CmsPages::find()->where(['is_active' => 1])->andFilterWhere(['or', ['id' => $id],[ 'identifier' => $id]])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
