<?php

namespace frontend\models;

use Yii;
use frontend\components\MappingValidator;
use backend\models\MembersAttributes;

/**
 * This is the model class for table "members_attributes_answers".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 */
class MembersAttributesAnswers extends \yii\db\ActiveRecord
{
    private $dynamicAttributesRules = [];
    private $dynamicAttributes = [];
    private $dynamicAttributesLabels = [];

    const SCENARIO_MAPPING = 'mapping';

    public function __construct($attributes_id = null) {
        if ($attributes_id) {
            $this->generateDynamicAttributes($attributes_id);
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%members_attributes_answers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules_general = [
            [['attribute_id', 'option_id', 'member_id'], 'integer'],
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
            'attribute_id' => Yii::t('frontend', 'Attribute ID'),
            'option_id' => Yii::t('frontend', 'Option ID'),
            'value' => Yii::t('frontend', 'Value'),
            'member_id' => Yii::t('frontend', 'Member ID'),
        ];

        return array_merge($this->dynamicAttributesLabels, $labels_general);
    }

    function attributes()
    {
        $attributes = parent::attributes();
        return array_merge($attributes, $this->dynamicAttributes);
    }

    private function generateDynamicAttributes ($attributes_id) {
        $model = MembersAttributes::find(['id in' => $attributes_id])->all();

        foreach($model as $attribute) {
                $message = Yii::t('frontend', 'Please answer the question: "{question}"', [
                    'question' => $attribute->label,
                ]);

                array_push($this->dynamicAttributesRules, [["value_$attribute->id"], MappingValidator::className(), 'skipOnEmpty' => false, 'message' => $message]);

            $this->dynamicAttributes[] = "value_$attribute->id";
            $this->dynamicAttributesLabels["value_$attribute->id"] = $attribute->label;
        }
    }
}
