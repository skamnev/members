<?php

namespace frontend\models;

use frontend\components\MembersCodesComponent;
use Yii;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cms_pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $content_heading
 * @property string $identifier
 * @property string $code_id
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_active
 * @property integer $sort_order
 *
 * @property CmsPagesLang[] $cmsPagesLangs
 */
class CmsPages extends \yii\db\ActiveRecord
{
    const IS_ACTIVE = 1;
    const NOT_ACTIVE = 0;

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
                'langForeignKey' => 'page_id',
                'tableName' => "{{%cms_pages_lang}}",
                'attributes' => [
                    'title', 'content', 'content_heading', 'meta_keywords', 'meta_description'
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_pages}}';
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
            [['content', 'meta_keywords', 'meta_description'], 'string'],
            [['code_id', 'no_code_id', 'category_id', 'created_at', 'updated_at'], 'safe'],
            [['is_active', 'sort_order'], 'integer'],
            [['title', 'content_heading'], 'string', 'max' => 255],
            [['identifier'], 'string', 'max' => 128]
        ];


        return array_merge($language_rules, $rules_general);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'Page ID'),
            'title' => Yii::t('backend', 'Page Title'),
            'content' => Yii::t('backend', 'Page Content'),
            'content_heading' => Yii::t('backend', 'Page Heading'),
            'identifier' => Yii::t('backend', 'Page Url Key'),
            'code_id' => Yii::t('backend', 'Include Page Code IDs'),
            'no_code_id' => Yii::t('backend', 'Exclude Page Code IDs'),
            'mapping_id' => Yii::t('backend', 'Category ID'),
            'category_id' => Yii::t('backend', 'Category'),
            'meta_keywords' => Yii::t('backend', 'Page Keywords'),
            'meta_description' => Yii::t('backend', 'Page Description'),
            'created_at' => Yii::t('backend', 'Page Created'),
            'updated_at' => Yii::t('backend', 'Page Updated'),
            'is_active' => Yii::t('backend', 'Is Page Active'),
            'sort_order' => Yii::t('backend', 'Page Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsPagesLangs()
    {
        return $this->hasMany(CmsPagesLang::className(), ['page_id' => 'id']);
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        if (empty($this->identifier)) {
            $this->identifier = str_replace(' ', '-', strtolower($this->title));
        }

        if (!empty($this->code_id)) {
            $code_ids = '[' . implode("],[", $this->code_id) . ']';
            $this->code_id = $code_ids;
        }

        if (!empty($this->no_code_id)) {
            $no_code_ids = '[' . implode("],[", $this->no_code_id) . ']';
            $this->no_code_id = $no_code_ids;
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->code_id = preg_split('/[\[\],]/i', $this->code_id, -1, PREG_SPLIT_NO_EMPTY);//explode(',', $this->code_id);
        $this->no_code_id = preg_split('/[\[\],]/i', $this->no_code_id, -1, PREG_SPLIT_NO_EMPTY);//explode(',', $this->no_code_id);
        $this->content = MembersCodesComponent::filterOffMemberCodes($this->content);
    }

    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => CmsPages::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => CmsPages::IS_ACTIVE]), 'id', 'value');
    }
}
