<?php

namespace frontend\models;

use backend\models\MappingCategories;
use frontend\components\MappingValidator;
use backend\models\MappingQuestions;
use backend\models\MappingQuestionsToOptions;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "members_questions_answers".
 *
 * @property integer $id
 * @property integer $question_id
 * @property string $value
 */
class MembersQuestionsAnswers extends \yii\db\ActiveRecord
{
    private $dynamicAttributesRules = [];
    private $dynamicAttributes = [];
    private $dynamicAttributesLabels = [];

    const SCENARIO_MAPPING = 'mapping';

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function __construct($categoryId = null) {
        if ($categoryId) {
            $this->generateDynamicAttributes($categoryId);
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%members_questions_answers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules_general = [
            [['question_id', 'option_id', 'member_id'], 'integer'],
        ];

        return array_merge($rules_general, $this->dynamicAttributesRules);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels_general = [
            'id' => Yii::t('frontend', 'ID'),
            'question_id' => Yii::t('frontend', 'Question ID'),
            'option_id' => Yii::t('frontend', 'Option ID'),
            'value' => Yii::t('frontend', 'Value'),
            'other' => Yii::t('frontend', 'Other'),
            'member_id' => Yii::t('frontend', 'Member ID'),
        ];

        return array_merge($this->dynamicAttributesLabels, $labels_general);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionOption()
    {
        return $this->hasOne(MappingQuestionsToOptions::className(), ['id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMappingQuestion()
    {
        return $this->hasOne(MappingQuestions::className(), ['id' => 'question_id']);
    }

    function attributes()
    {
        $attributes = parent::attributes();
        return array_merge($attributes, $this->dynamicAttributes);
    }

    private function generateDynamicAttributes ($categoryId) {
        $questionsModel = MappingQuestions::find()->where(['category_id' => $categoryId])->all();

        foreach($questionsModel as $question) {
            $message = Yii::t('frontend', 'Please answer the question: "{question}"', [
                'question' => $question->title,
            ]);

            if ($question->fieldsTypes[0]->has_other_field && $question->has_other) {
                array_push($this->dynamicAttributesRules, [["other_$question->id"], MappingValidator::className(), 'skipOnEmpty' => true, 'message' => $message]);

                $this->dynamicAttributes[] = "other_$question->id";
                $this->dynamicAttributesLabels["other_$question->id"] = $question->title . ' ' . Yii::t('frontend', 'other');
            }

            array_push($this->dynamicAttributesRules, [["value_$question->id"], MappingValidator::className(), 'skipOnEmpty' => false, 'message' => $message]);

            $this->dynamicAttributes[] = "value_$question->id";
            $this->dynamicAttributesLabels["value_$question->id"] = $question->title;

        }
    }
}
