<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CmsFaq;

/**
 * SearchCmsFaq represents the model behind the search form about `backend\models\CmsFaq`.
 */
class SearchCmsFaq extends CmsFaq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'sort_order'], 'integer'],
            [['title', 'content', 'identifier', 'category_id', 'created_at', 'updated_at', 'author'], 'safe'],
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
        $query = CmsFaq::find()->where(['is_active' => 1])
            ->andFilterWhere(['like', 'cms_faq_lang.title', $this->title])
            ->orFilterWhere(['like', 'cms_faq_lang.content', $this->content]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['sort_order'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sort_order' => $this->sort_order,
        ]);*/

        $query->joinWith(['cmsFaqLangs' => function ($query) {
            $query->where(['language' => Yii::$app->language]);
        }]);

        return $dataProvider;
    }
}
