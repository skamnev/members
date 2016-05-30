<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "general_settings".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 */
class GeneralSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'general_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name'),
            'value' => Yii::t('backend', 'Value'),
        ];
    }
}
