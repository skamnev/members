<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CmsMealPlan;

/**
 * SearchCmsMealPlan represents the model behind the search form about `backend\models\CmsMealPlan`.
 */
class SearchMealPlans extends CmsMealPlan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'sort_order'], 'integer'],
            [['title', 'content', 'identifier', 'code_id', 'category_id', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CmsMealPlan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ]);

        $query//->andFilterWhere(['like', 'cms_pages.title', $this->title])
            ->andFilterWhere(['like', 'cms_pages_lang.title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'code_id', $this->code_id]);

        $query->joinWith(['cmsMealplanLangs' => function ($query) {
            $query->where(['language' => Yii::$app->language]);
        }]);

        return $dataProvider;
    }
}
