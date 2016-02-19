<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cms_faq".
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
 * @property CmsFaqLang[] $cmsFaqsLangs
 */
class CmsFaq extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'faq_id',
                'tableName' => "{{%cms_faq_lang}}",
                'attributes' => [
                    'title', 'content'
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
        return '{{%cms_faq}}';
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
            [['content'], 'string'],
            [['category_id', 'created_at', 'updated_at'], 'safe'],
            [['is_active', 'author', 'sort_order'], 'integer'],
            [['category_id'], 'required', 'message' => Yii::t('backend', 'Please select at least one Category')],
            [['title'], 'string', 'max' => 255],
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
            'id' => Yii::t('backend', 'Faq ID'),
            'author' => Yii::t('backend', 'Author ID'),
            'title' => Yii::t('backend', 'Faq Title'),
            'content' => Yii::t('backend', 'Faq Content'),
            'identifier' => Yii::t('backend', 'Faq Url Key'),
            'mapping_id' => Yii::t('backend', 'Mapping Category ID'),
            'category_id' => Yii::t('backend', 'Category'),
            'is_active' => Yii::t('backend', 'Is Faq Active'),
            'sort_order' => Yii::t('backend', 'Faq Order'),
            'created_at' => Yii::t('backend', 'Faq Created'),
            'updated_at' => Yii::t('backend', 'Faq Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsFaqLangs()
    {
        return $this->hasMany(CmsFaqLang::className(), ['faq_id' => 'id']);
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    public function beforeValidate() {
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->author = Yii::$app->user->id;
        }

        if (empty($this->identifier)) {
            $this->identifier = str_replace(' ', '-', strtolower($this->title));
        }

        if (is_array($this->category_id)) {
            $category_ids = '[' . implode("],[", $this->category_id) . ']';
            $this->category_id = $category_ids;
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->category_id = preg_split('/[\[\],]/i', $this->category_id, -1, PREG_SPLIT_NO_EMPTY);//explode(',', $this->category_id);
    }

    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => CmsFaq::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => CmsFaq::IS_ACTIVE]), 'id', 'value');
    }
}
