<?php

namespace backend\models;

use frontend\models\User;
use Yii;

/**
 * This is the model class for table "members".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $braintree_customer_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property MembersAttributesAnswers[] $membersAttributesAnswers
 * @property MembersQuestionsAnswers[] $membersQuestionsAnswers
 * @property MembersWeightTracker[] $membersWeightTrackers
 */
class Members extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%members}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['braintree_customer_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'username' => Yii::t('backend', 'Username'),
            'firstname' => Yii::t('backend', 'First Name'),
            'lastname' => Yii::t('backend', 'Last Name'),
            'auth_key' => Yii::t('backend', 'Auth Key'),
            'password_hash' => Yii::t('backend', 'Password Hash'),
            'password_reset_token' => Yii::t('backend', 'Password Reset Token'),
            'email' => Yii::t('backend', 'Email'),
            'braintree_customer_id' => Yii::t('backend', 'Braintree Customer ID'),
            'status' => Yii::t('backend', 'Active'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersAttributesAnswers()
    {
        return $this->hasMany(MembersAttributesAnswers::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersQuestionsAnswers()
    {
        return $this->hasMany(MembersQuestionsAnswers::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersWeightTrackers()
    {
        return $this->hasMany(MembersWeightTracker::className(), ['member_id' => 'id']);
    }

    public static function getStatusLabel ($status) {
        $label = '<span class="';
        switch ($status) {
            case \frontend\models\User::STATUS_UNPAID:
            case \frontend\models\User::STATUS_INACTIVE:
                $label .= 'btn-xs btn-danger btn">' . Yii::t('backend', 'Not Paid') . '</span>';
                break;
            case \frontend\models\User::STATUS_PAID:
            case \frontend\models\User::STATUS_ACTIVE:
                $label .= 'btn-xs btn-success btn">' . Yii::t('backend', 'Paid') . '</span>';
                break;
            default:
                $label .= 'btn-xs btn-danger btn">' . Yii::t('backend', '(undefined)') . '</span>';
                break;
        }

        return $label;
    }
}
