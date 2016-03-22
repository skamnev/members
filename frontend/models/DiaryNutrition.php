<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "diary_nutrition".
 *
 * @property integer $id
 * @property string $value
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class DiaryNutrition extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
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
        return 'diary_nutrition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'comment'], 'string'],
            [['member_id'], 'integer'],
            [['member_id'], 'required'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'value' => Yii::t('frontend', 'Value'),
            'comment' => Yii::t('frontend', 'Comment'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
        ];
    }
}
