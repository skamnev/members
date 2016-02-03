<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "members_mapping_answers".
 *
 * @property integer $id
 * @property integer $question_id
 * @property string $value
 */
class MembersMappingAnswers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'members_mapping_answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'option_id'], 'integer'],
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
            'question_id' => Yii::t('backend', 'Question ID'),
            'option_id' => Yii::t('backend', 'Option ID'),
            'value' => Yii::t('backend', 'Value'),
        ];
    }
}
