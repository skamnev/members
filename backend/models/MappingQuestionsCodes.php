<?php

namespace backend\models;

use Yii;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "mapping_questions_codes".
 *
 * @property integer $id
 * @property string $code
 * @property string $description
 */
class MappingQuestionsCodes extends \yii\db\ActiveRecord
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
                //'languageField' => 'backend',
                //'localizedPrefix' => '',
                //'forceOverwrite' => false',
                //'dynamicLangClass' => true',
                //'langClassName' => PostLang::className(), // or namespace/for/a/class/PostLang
                'defaultLanguage' => $languageDefault->url,
                'langForeignKey' => 'code_id',
                'tableName' => "{{%mapping_questions_codes_lang}}",
                'attributes' => [
                    'description',
                ]
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mapping_questions_codes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['is_group'], 'integer'],
            [['code'], 'string', 'max' => 4],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'code' => Yii::t('backend', 'Code'),
            'is_group' => Yii::t('backend', 'Use as group?'),
            'description' => Yii::t('backend', 'Description'),
        ];
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }
}
