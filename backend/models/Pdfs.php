<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pdfs".
 *
 * @property integer $id
 * @property string $name
 * @property integer $order
 * @property string $file
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PdfsLang[] $pdfsLangs
 * @property PdfsRules[] $pdfsRules
 */
class Pdfs extends \yii\db\ActiveRecord
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
                'langForeignKey' => 'pdf_id',
                'tableName' => "{{%pdfs_lang}}",
                'attributes' => [
                    'name',
                ]
            ],
            [
                'class' => '\yiidreamteam\upload\FileUploadBehavior',
                'attribute' => 'file',
                'filePath' => Yii::getAlias('@backend') . '/web/media/pdfs/pages/[[pk]]/[[filename]].[[extension]]',
                'fileUrl' => Yii::getAlias('@backend') . '/web/media/pdfs/pages/[[pk]]/[[filename]].[[extension]]',
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
        return '{{%pdfs}}';
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
                array_push($language_rules, [['name'], 'required', 'message' => $message]);
            } else {
                array_push($language_rules, [['name_' . $language->url], 'required', 'message' => $message]);
            }
        }

        $rules_general = [
            [['order', 'is_active'], 'integer'],
            ['file', 'file', 'extensions' => 'pdf'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];

        return array_merge($language_rules, $rules_general);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'name' => Yii::t('cms', 'Name'),
            'order' => Yii::t('cms', 'Sort Order'),
            'file' => Yii::t('cms', 'File'),
            'is_active' => Yii::t('cms', 'Is Active'),
            'created_at' => Yii::t('cms', 'Created At'),
            'updated_at' => Yii::t('cms', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdfsLangs()
    {
        return $this->hasMany(PdfsLang::className(), ['pdf_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdfsRules()
    {
        return $this->hasMany(PdfsRules::className(), ['pdf_id' => 'id']);
    }

    public static function dropdownActive() {
        return ArrayHelper::map(array(
            ['value' => Yii::t('backend','No'), 'id' => self::NOT_ACTIVE],
            ['value' => Yii::t('backend','Yes'), 'id' => self::IS_ACTIVE]), 'id', 'value');
    }

    public static function find()
    {
        $q = new MultilingualQuery(get_called_class());
        $q->localized();
        return $q;
    }
}
