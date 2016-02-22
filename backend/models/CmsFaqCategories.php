<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "cms_faq_categories".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort_order
 * @property integer $created_at
 * @property string $updated_at
 */
class CmsFaqCategories extends \yii\db\ActiveRecord
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
                'tableName' => "{{%cms_faq_categories_lang}}",
                'attributes' => [
                    'title',
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
        return '{{%cms_faq_categories}}';
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
                array_push($language_rules, [['title'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['title_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['status', 'sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'title' => Yii::t('backend', 'Title'),
            'status' => Yii::t('backend', 'Active'),
            'sort_order' => Yii::t('backend', 'Sort Order'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsFaqCategoriesLangs()
    {
        return $this->hasMany(CmsFaqCategoriesLang::className(), ['category_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsFaqList($_id)
    {
        return CmsFaq::find()->where(['like', 'category_id',"[$_id]"]);
    }

    public function getStatusLabel()
    {
        return $this->status ? Yii::t('backend','Yes') : Yii::t('backend','No');
    }


    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => CmsFaq::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => CmsFaq::IS_ACTIVE]), 'id', 'value');
    }
}
