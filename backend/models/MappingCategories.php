<?php

namespace backend\models;

use Yii;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mapping_categories".
 *
 * @property integer $id
 * @property string $name
 */
class MappingCategories extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'category_id',
                'tableName' => "{{%mapping_categories_lang}}",
                'attributes' => [
                    'name',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mapping_categories}}';
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
            $message = Yii::t('backend', 'Please specify {name} category name', [
                'name' => $language->name,
            ]);

            if ($languageDefault->url == $language->url) {
                array_push($language_rules, [['name'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['name_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['name'], 'string', 'max' => 128],
            [['is_active', 'duration', 'sort_order'], 'integer'],
            [['attributes_id'], 'safe'],
        ];


        return array_merge($language_rules, $rules_general);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(MappingQuestions::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMappingCategoriesLang()
    {
        return $this->hasMany(MappingCategoriesLang::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name'),
            'duration' => Yii::t('backend', 'Duration'),
            'attributes_id' => Yii::t('backend', 'Attributes ID'),
        ];
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }

    /*public function beforeSave($insert)
    {
        $attributes_id = implode(",", $this->attributes_id);
        $this->attributes_id = $attributes_id;
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->attributes_id = explode(',', $this->attributes_id);
    }*/

    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => CmsPages::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => CmsPages::IS_ACTIVE]), 'id', 'value');
    }
}
