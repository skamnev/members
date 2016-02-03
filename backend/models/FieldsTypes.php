<?php

namespace backend\models;

use Yii;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "fields_type".
 *
 * @property integer $id
 * @property integer $title
 * @property integer $has_options
 */
class FieldsTypes extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'type_id',
                'tableName' => "{{%fields_type_lang}}",
                'attributes' => [
                    'title',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fields_type}}';
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
            $message = Yii::t('backend', 'Please specify {field} field', [
                'field' => $language->name,
            ]);

            if ($languageDefault->url == $language->url) {
                array_push($language_rules, [['title'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['title_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['title'], 'string', 'max' => 128],
            [['has_options', 'has_other_field'], 'integer']
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
            'title' => Yii::t('backend', 'Name'),
            'has_options' => Yii::t('backend', 'Has Options'),
            'has_other_field' => Yii::t('backend', 'Has Other Text Field'),
        ];
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }
}
