<?php

namespace backend\models;

use Yii;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use backend\models\MappingQuestionsCodes;

/**
 * This is the model class for table "mapping_questions_to_options".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $code_id
 * @property string $title
 * @property integer $lang_id
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 */
class MappingQuestionsToOptions extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        $languagesModel = Lang::find()->all();
        $languageDefault = Lang::findOne(['default' => 1]);

        $languages = [];
        foreach ($languagesModel as $model) {
            $languages[$model->url] = $model->name;
        }

        return [
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => $languages,
                'defaultLanguage' => $languageDefault->url,
                'langForeignKey' => 'option_id',
                'tableName' => "{{%mapping_questions_to_options_lang}}",
                'attributes' => [
                    'title',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mapping_questions_to_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $languagesModel = Lang::find()->all();
        $languageDefault = Lang::findOne(['default' => 1]);

        $language_rules = [];
        foreach ($languagesModel as $language) {
            $message = Yii::t('backend', 'Please specify {question} question', [
                'question' => $language->name,
            ]);

            if ($languageDefault->url == $language->url) {
                array_push($language_rules, [['title'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['title_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['question_id'], 'required'],
            [['question_id', 'order'], 'integer'],
            [['code_id', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];

        return array_merge($language_rules, $rules_general);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'question_id' => Yii::t('backend', 'Question ID'),
            'code_id' => Yii::t('backend', 'Code IDs'),
            'title' => Yii::t('backend', 'Title'),
            //'lang_id' => Yii::t('backend', 'Lang ID'),
            'order' => Yii::t('backend', 'Sort Order'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    public function getMappingQuestionsCodes() {
        return MappingQuestionsCodes::find()->where(['id' => $this->code_id])->multilingual()->one();
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }

    public function beforeSave($insert)
    {
        $code_ids = implode(",", $this->code_id);
        $this->code_id = $code_ids;
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        //$this->code_id = unserialize($this->code_id);
        $this->code_id = explode(',', $this->code_id);
    }
}
