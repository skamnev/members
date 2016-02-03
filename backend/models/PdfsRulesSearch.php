<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PdfsRules;

/**
 * PdfsRulesSearch represents the model behind the search form about `backend\models\PdfsRules`.
 */
class PdfsRulesSearch extends PdfsRules
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pdf_id', 'question_id', 'options_id', 'progress', 'is_active'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at'], 'safe'],
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
        $query = PdfsRules::find();

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
            'pdf_id' => $this->pdf_id,
            'question_id' => $this->question_id,
            'options_id' => $this->options_id,
            'progress' => $this->progress,
            'is_active' => $this->is_active,
            'created_at' => $this->create_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
