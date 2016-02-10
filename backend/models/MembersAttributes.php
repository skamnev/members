<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "members_attributes".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $label
 * @property integer $order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MembersAttributesLang[] $membersAttributesLangs
 */
class MembersAttributes extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'attribute_id',
                'tableName' => "{{%members_attributes_lang}}",
                'attributes' => [
                    'label',
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
        return '{{%members_attributes}}';
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
                array_push($language_rules, [['label'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['label_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['type_id', 'is_active', 'is_required', 'order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['label'], 'string', 'max' => 255]
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
            'type_id' => Yii::t('backend', 'Type ID'),
            'label' => Yii::t('backend', 'Label'),
            'order' => Yii::t('backend', 'Order'),
            'is_active' => Yii::t('backend', 'Active'),
            'is_required'=> Yii::t('backend', 'Required'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesAnswers()
    {
        return $this->hasOne(MembersAttributesAnswers::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(MembersAttributesToOptions::className(), ['attribute_id' => 'id']);
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
