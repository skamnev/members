<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cms_mealplan".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $identifier
 * @property string $code_id
 * @property string $no_code_id
 * @property string $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_active
 * @property integer $sort_order
 * @property integer $author
 * @property string $publish_date
 *
 * @property CmsMealplanLang[] $cmsMealplanLangs
 */
class CmsMealPlan extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'mealplan_id',
                'tableName' => "{{%cms_mealplan_lang}}",
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
        return '{{%cms_mealplan}}';
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
            [['code_id', 'no_code_id', 'created_at', 'updated_at'], 'safe'],
            [['is_active', 'author', 'sort_order'], 'integer'],
            [['publish_date'], 'date', 'format' => 'dd.mm.yyyy'],
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
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'content' => Yii::t('backend', 'Content'),
            'identifier' => Yii::t('backend', 'Url Key'),
            'code_id' => Yii::t('backend', 'Code IDs'),
            'no_code_id' => Yii::t('backend', 'Code IDs we have to exclude'),
            'created_at' => Yii::t('backend', 'Created'),
            'updated_at' => Yii::t('backend', 'Updated'),
            'is_active' => Yii::t('backend', 'Status Active'),
            'sort_order' => Yii::t('backend', 'Order'),
            'author' => Yii::t('backend', 'Author'),
            'publish_date' => Yii::t('backend', 'Publish Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsMealplanLangs()
    {
        return $this->hasMany(CmsMealplanLang::className(), ['mealplan_id' => 'id']);
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

        if (!empty($this->publish_date)) {
            $this->publish_date = date('Y-m-d H:i:s',strtotime($this->publish_date));
        }

        if (empty($this->identifier)) {
            $this->identifier = str_replace(' ', '-', strtolower($this->title));
        }

        if (is_array($this->category_id)) {
            $category_ids = '[' . implode("],[", $this->category_id) . ']';
            $this->category_id = $category_ids;
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
        $this->category_id = preg_split('/[\[\],]/i', $this->category_id, -1, PREG_SPLIT_NO_EMPTY);//explode(',', $this->category_id);
        $this->code_id = preg_split('/[\[\],]/i', $this->code_id, -1, PREG_SPLIT_NO_EMPTY);//explode(',', $this->code_id);
        $this->no_code_id = preg_split('/[\[\],]/i', $this->no_code_id, -1, PREG_SPLIT_NO_EMPTY);//explode(',', $this->no_code_id);
    }

    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => CmsMealPlan::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => CmsMealPlan::IS_ACTIVE]), 'id', 'value');
    }
}
