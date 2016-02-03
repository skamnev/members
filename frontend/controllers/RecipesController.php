<?php

namespace frontend\controllers;

use backend\models\MappingCategories;
use backend\models\CmsRecipesCategories;
use backend\models\MappingQuestionsCodes;
use frontend\components\MembersCodesComponent;
use frontend\models\MembersQuestionsAnswers;
use frontend\models\CmsRecipes;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * RecipesController implements the CRUD actions for CmsRecipes model.
 */
class RecipesController extends MainController
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
            'query' => CmsRecipesCategories::find()->where(['status' => 1]),
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC]]
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

        $recipesQuery = CmsRecipes::find()->where(['is_active' => 1]);


        //add default group to exclude codes array
        if (!empty($memberGroupCode)) {
            $memberAnswersCodes['exclude'][] = ['not like', 'no_code_id', "[$memberGroupCode]"];
        }
        //add conditions to the where instance if exclude codes array not empty
        if (count($memberAnswersCodes['exclude'])>0) {
            $recipesQuery->andWhere(array_merge(['and'],$memberAnswersCodes['exclude']))
                ->orWhere(['no_code_id' => NULL]);
        }
        //add default group to include codes array
        if (!empty($memberGroupCode)) {
            $memberAnswersCodes['include'][] = ['like', 'code_id', "[$memberGroupCode]"];
        }
        //add conditions to the where instance if include codes array not empty
        if (count($memberAnswersCodes['include'])>0) {
            $recipesQuery->andWhere(array_merge(['or'],$memberAnswersCodes['include']))
                ->orWhere(['code_id' => '']);
        }

        $recipesQuery->andWhere(['like', 'category_id',"[$id]"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $recipesQuery,
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC]]
        ]);

        $category = CmsRecipesCategories::find($id)->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category' => $category
        ]);
    }

    /**
     * Displays a single CmsRecipes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $category = CmsRecipesCategories::findOne(['id' => $model->category_id]);

        return $this->render('view', [
            'model' => $model,
            'category' => $category
        ]);
    }

    /**
     * Finds the CmsRecipes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsRecipes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsRecipes::find()->where(['is_active' => 1])->andFilterWhere(['or', ['id' => $id],[ 'identifier' => $id]])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
