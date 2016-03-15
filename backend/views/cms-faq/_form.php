<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\MappingQuestionsCodes;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsFaq */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(Yii::getAlias('@web/js/redactor.emotions.js'), ['depends' => [
    'yii\web\YiiAsset'],
]);
$this->registerJsFile(Yii::getAlias('@web/js/emojione.js'), ['depends' => [
    'yii\web\YiiAsset'],
]);
$this->registerCssFile(Yii::getAlias('@web/css/redactor.emotions.css'));
$this->registerCssFile(Yii::getAlias('@web/css/emojione.min.css'));
?>

<div class="cms-faq-form">
    <?php $form = ActiveForm::begin([]); ?>

    <?= $form->errorSummary($model); ?>

    <?php
    $language_items = [];
    $redactorClientOptions = [
        'imageManagerJson' => ['/redactor/upload/image-json'],
        'imageUpload' => ['/redactor/upload/image'],
        'fileUpload' => ['/redactor/upload/file'],
        'lang' => Yii::$app->language,
        'plugins' => ['fontcolor','imagemanager', 'emotions']
    ];

    foreach($languages as $key => $language) {
        $field_title = $form->field($model, "title_$language->url")->textInput(['maxlength' => true])->label('FAQ Title');

        $field_content = $form->field($model, "content_$language->url")->widget(\yii\redactor\widgets\Redactor::className(),[
            'clientOptions' => $redactorClientOptions
        ]);

        if ($languageDefault->url == $language->url) {
            $field_title = $form->field($model, "title")->textInput(['maxlength' => true]);

            $field_content = $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(),[
                'clientOptions' => $redactorClientOptions
            ]);

        }

        $language_items[] = [
            'label' => Yii::t('backend',$language->name),
            'content' => "<p>$field_title $field_content</p>",
            'active' => $key==0
        ];
    }

    echo Tabs::widget([
        'items' => $language_items
    ]);
    ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\backend\models\CmsFaqCategories::find()->all(), 'id', 'title'), ['multiple' => 'true', 'size' => 7/*, 'prompt'=>Yii::t('backend', 'Select Categories')*/])->label(Yii::t('backend', 'Select Categories')) ?>

    <?= $form->field($model, 'identifier')->textInput(['maxlength' => true]) ?>
   
    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'is_active')->checkbox(['label' => Yii::t('backend','Active')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php /*
$this->registerJs("

$('#cmsfaq-title_en').keyup(function(){
    if ($('#cmsfaq-identifier').val() == '') {
        console.log('it alsow workds!');
        identifier = $(this).val();
        identifier=identifier.replace(' ','-');
        console.log(identifier);
        $('#cmsfaq-identifier').val(identifier);
    }
});
", yii\web\View::POS_READY , 'my-options');
*/?>