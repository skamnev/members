<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "members_attributes_answers".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 */
class MembersAttributesAnswers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'members_attributes_answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id'], 'integer'],
            [['value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'attribute_id' => Yii::t('backend', 'Attribute ID'),
            'value' => Yii::t('backend', 'Value'),
        ];
    }
}
