<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "members_progress".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $category_id
 * @property integer $progress
 */
class MembersProgress extends \yii\db\ActiveRecord
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
        return 'members_progress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'category_id', 'progress'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'member_id' => Yii::t('frontend', 'Member ID'),
            'category_id' => Yii::t('frontend', 'Category ID'),
            'progress' => Yii::t('frontend', 'Progress'),
        ];
    }
}
