<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "mapping_questions".
 *
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property integer $type_id
 *
 * @property MappingCategories $category
 */
class MappingQuestions extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'question_id',
                'tableName' => "{{%mapping_questions_lang}}",
                'attributes' => [
                    'title',
                ]
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mapping_questions}}';
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
            [['category_id', 'type_id'], 'required'],
            [['category_id', 'is_active', 'is_required', 'type_id', 'has_other', 'order'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
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
            'title' => Yii::t('backend', 'Title'),
            'category_id' => Yii::t('backend', 'Category ID'),
            'is_active' => Yii::t('backend', 'Active'),
            'is_required'=> Yii::t('backend', 'Required'),
            'has_other'=> Yii::t('backend', 'Has Other'),
            'type_id' => Yii::t('backend', 'Type'),
            'order' => Yii::t('backend', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(MappingCategories::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionsAnswers()
    {
        return $this->hasOne(MembersQuestionsAnswers::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(MappingQuestionsToOptions::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldsTypes()
    {
        return $this->hasMany(FieldsTypes::className(), ['id' => 'type_id']);
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }
}
