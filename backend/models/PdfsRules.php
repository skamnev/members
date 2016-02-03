<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;

/**
 * This is the model class for table "pdfs_rules".
 *
 * @property integer $id
 * @property integer $pdf_id
 * @property integer $question_id
 * @property integer $options_id
 * @property string $name
 * @property string $description
 * @property integer $progress
 * @property integer $is_active
 * @property string $create_at
 * @property string $updated_at
 *
 * @property Pdfs $pdf
 * @property PdfsRulesLang[] $pdfsRulesLangs
 */
class PdfsRules extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
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
        return '{{%pdfs_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'question_id', 'options_id'], 'required'],
            [['pdf_id', 'category_id', 'question_id', 'progress', 'is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'pdf_id' => Yii::t('cms', 'Pdf ID'),
            'question_id' => Yii::t('cms', 'Question ID'),
            'options_id' => Yii::t('cms', 'Options ID'),
            'category_id' => Yii::t('cms', 'Category ID'),
            'description' => Yii::t('cms', 'Description'),
            'progress' => Yii::t('cms', 'Progress'),
            'is_active' => Yii::t('cms', 'Is Active'),
            'created_at' => Yii::t('cms', 'Create At'),
            'updated_at' => Yii::t('cms', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdf()
    {
        return $this->hasOne(Pdfs::className(), ['id' => 'pdf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdfQuestions()
    {
        return $this->hasOne(MappingQuestions::className(), ['id' => 'question_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdfOptions()
    {
        return MappingQuestionsToOptions::find()->where(['id' => $this->options_id])->multilingual()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdfsRulesLangs()
    {
        return $this->hasMany(PdfsRulesLang::className(), ['pdf_rule_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $options_id = implode(",", array_filter($this->options_id));
        $this->options_id = $options_id;
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        //$this->code_id = unserialize($this->code_id);
        $this->options_id = explode(',', $this->options_id);
    }
}
