<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CmsRecipes;

/**
 * SearchCmsRecipes represents the model behind the search form about `backend\models\CmsRecipes`.
 */
class SearchCmsRecipes extends CmsRecipes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort_order'], 'integer'],
            [['is_active'], 'string'],
            [['title', 'content', 'content_heading', 'identifier', 'code_id', 'category_id', 'meta_keywords', 'meta_description', 'created_at', 'updated_at'], 'safe'],
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
        $query = CmsRecipes::find();

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

        $query->joinWith(['cmsRecipesLangs' => function ($query) {
            $query->where(['language' => Yii::$app->language]);
        }]);

        $query//->andFilterWhere(['like', 'title', $this->title])
        ->andFilterWhere(['like', 'cms_recipes_lang.title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'content_heading', $this->content_heading])
            ->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'code_id', $this->code_id])
            ->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
