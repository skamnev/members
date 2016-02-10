<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "cms_recipes_categories".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CmsRecipesCategoriesLang[] $cmsRecipesCategoriesLangs
 */
class CmsRecipesCategories extends \yii\db\ActiveRecord
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
                'tableName' => "{{%cms_recipes_categories_lang}}",
                'attributes' => [
                    'title',
                ]
            ],
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'main_img',
                'thumbs' => [
                    'thumb' => ['width' => 300, 'height' => 200],
                ],
                'filePath' => '@webroot/media/cms/recipes/categories/[[pk]].[[extension]]',
                'fileUrl' => '@web/media/cms/recipes/categories/[[pk]].[[extension]]',
                'thumbPath' => '@webroot/media/cms/recipes/categories/[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => '@web/media/cms/recipes/categories/[[profile]]_[[pk]].[[extension]]',
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
        return '{{%cms_recipes_categories}}';
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
            ['main_img', 'file', 'extensions' => 'jpeg, jpg, gif, png'],
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
    public function getCmsRecipesCategoriesLangs()
    {
        return $this->hasMany(CmsRecipesCategoriesLang::className(), ['category_id' => 'id']);
    }

    public function getStatusLabel()
    {
        return $this->status ? Yii::t('backend','Yes') : Yii::t('backend','No');
    }

    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => CmsPages::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => CmsPages::IS_ACTIVE]), 'id', 'value');
    }
}
