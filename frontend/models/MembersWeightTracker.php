<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "members_weight_tracker".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Members $member
 */
class MembersWeightTracker extends \yii\db\ActiveRecord
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
        return '{{%members_weight_tracker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'value'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'value' => 'Value',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Members::className(), ['id' => 'member_id']);
    }
}
