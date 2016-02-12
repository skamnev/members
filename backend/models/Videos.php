<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "videos".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $file
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Videos extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'video_id',
                'tableName' => "{{%videos_lang}}",
                'attributes' => [
                    'title', 'description',
                ]
            ],
            [
                'class' => '\yiidreamteam\upload\FileUploadBehavior',
                'attribute' => 'file',
                'filePath' => '@webroot/media/videos/[[pk]]/[[filename]].[[extension]]',
                'fileUrl' => '@web/media/videos/[[pk]]/[[filename]].[[extension]]',
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
        return '{{%videos}}';
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
            $message = Yii::t('backend', 'Please specify {name} name', [
                'name' => $language->name,
            ]);

            if ($languageDefault->url == $language->url) {
                array_push($language_rules, [['title'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['title_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['id', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            ['file', 'file', 'extensions' => ['pdf', 'mp4'], 'maxSize' => 1024 * 1024 * 300],
            [['title'], 'string', 'max' => 255]
        ];

        return array_merge($language_rules, $rules_general);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'title' => Yii::t('frontend', 'Title'),
            'description' => Yii::t('frontend', 'Description'),
            'file' => Yii::t('frontend', 'File Path'),
            'status' => Yii::t('frontend', 'Active'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
        ];
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }
    
    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => self::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => self::IS_ACTIVE]), 'id', 'value');
    }
}
