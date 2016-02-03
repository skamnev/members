<?php

namespace frontend\models;

use Yii;

use yii\db\Expression;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $url
 * @property string $local
 * @property string $name
 * @property integer $default
 * @property integer $date_update
 * @property integer $date_create
 */
class Lang extends \yii\db\ActiveRecord
{
    //current language variable
    static $current = null;

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_update'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['date_update'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    //get current language
    static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

    //set/update current language
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->local;
    }

    //get default language
    static function getDefaultLang()
    {
        return Lang::find()->where('`default` = :default', [':default' => 1])->one();
    }

    //get language from url
    static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = Lang::find()->where('url = :url', [':url' => $url])->one();
            if ( $language === null ) {
                return null;
            }else{
                return $language;
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->default) {
            Lang::updateAll(['`default`' => 0], '`default` = :default AND id <> :lang_id', [':default'=>1, ':lang_id' => $this->id]);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name'], 'required'],
            [['default'], 'integer'],
            [['date_update', 'date_create'], 'safe'],
            [['url', 'local', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'url' => Yii::t('frontend', 'Url'),
            'local' => Yii::t('frontend', 'Local'),
            'name' => Yii::t('frontend', 'Name'),
            'default' => Yii::t('frontend', 'Default'),
            'date_update' => Yii::t('frontend', 'Date Update'),
            'date_create' => Yii::t('frontend', 'Date Create'),
        ];
    }

    /**
     * @inheritdoc
     * @return LangQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LangQuery(get_called_class());
    }
}
