<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\MappingQuestionsCodes;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsPages */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(Yii::getAlias('@web/js/redactor.emotions.js'), ['depends' => [
    'yii\web\YiiAsset'],
]);
$this->registerJsFile(Yii::getAlias('@web/js/emojione.js'), ['depends' => [
    'yii\web\YiiAsset'],
]);

$this->registerJsFile(Yii::getAlias('@web/js/redactor.undo.js'), ['depends' => [
    'yii\web\YiiAsset'],
    'position' => \yii\web\View::POS_END
]);

$this->registerCssFile(Yii::getAlias('@web/css/redactor.emotions.css'));
$this->registerCssFile(Yii::getAlias('@web/css/emojione.min.css'));

?>
<div class="cms-pages-form">
    <p>
        <?= Html::img($model->getThumbFileUrl('main_img', 'thumb')) ?>
    </p>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>
    
    <p>
        
        <?//= Html::a(Yii::t('backend', 'Preview'), ["../../" . Yii::$app->language ."/article/$categoryModel->id/" . ($model->identifier?$model->identifier:$model->id)], ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
    </p>

    <div class="form-group">
        <?= Html::a( 'Back', ['cms-pages-categories/view', 'category_id' => $categoryModel->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Preview'), ["preview", 'category_id' => $categoryModel->id, 'id' => $model->id], ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
    </div>
    <p>
        <?php $frontend_url = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['/article/' . $categoryModel->id . '/' . ($model->identifier?$model->identifier:$model->id)]);?>
        <?= yii\bootstrap\Html::textInput('frontend_link', $frontend_url, ['maxlength' => true, 'style'=>'width: 100%', 'disabled' => 'disabled']);?>
    </p>

    <?= $form->errorSummary($model); ?>

    <?php
    $language_items = [];
    $redactorClientOptions = [
        'imageManagerJson' => ['/redactor/upload/image-json'],
        'imageUpload' => ['/redactor/upload/image'],
        'fileUpload' => ['/redactor/upload/file'],
        'lang' => Yii::$app->language,
        'plugins' => ['bufferbuttons','fontcolor','imagemanager', 'emotions']
    ];

    foreach($languages as $key => $language) {
        $field_title = $form->field($model, "title_$language->url")->textInput(['maxlength' => true])->label('Page Title');

        $field_content = $form->field($model, "content_$language->url")->widget(\yii\redactor\widgets\Redactor::className(),[
            'clientOptions' => $redactorClientOptions
        ]);

        $field_content_heading = $form->field($model, "content_heading_$language->url")->textInput(['maxlength' => true])->label(Yii::t('backend','Page Heading'));

        $field_meta_keywords = $form->field($model, "meta_keywords_$language->url")->textarea(['rows' => 4])->label(Yii::t('backend','Page Keywords'));

        $field_meta_description = $form->field($model, "meta_description_$language->url")->textarea(['rows' => 6])->label(Yii::t('backend','Page Description'));

        if ($languageDefault->url == $language->url) {
            $field_title = $form->field($model, "title")->textInput(['maxlength' => true]);

            $field_content = $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(),[
                'clientOptions' => $redactorClientOptions
            ]);

            $field_content_heading = $form->field($model, "content_heading")->textInput(['maxlength' => true]);
            $field_meta_keywords = $form->field($model, "meta_keywords")->textarea(['rows' => 4]);
            $field_meta_description = $form->field($model, "meta_description")->textarea(['rows' => 6]);
        }

        $language_items[] = [
            'label' => Yii::t('backend',$language->name),
            'content' => "<p>$field_title $field_content $field_content_heading $field_meta_keywords</p>",
            'active' => $language->url==Yii::$app->language
        ];
    }

    echo Tabs::widget([
        'items' => $language_items
    ]);
    ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\backend\models\CmsPagesCategories::find()->all(), 'id', 'title'), ['multiple' => 'true', 'size' => 7/*, 'prompt'=>Yii::t('backend', 'Select Categories')*/])->label(Yii::t('backend', 'Select Categories')) ?>

    <?= $form->field($model, 'identifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code_id')->dropDownList(ArrayHelper::map(MappingQuestionsCodes::find()->all(), 'id', 'code'), ['multiple' => 'true', 'size' => 7, 'prompt'=>Yii::t('backend', 'Select Include Codes')]) ?>

    <?= $form->field($model, 'no_code_id')->dropDownList(ArrayHelper::map(MappingQuestionsCodes::find()->all(), 'id', 'code'), ['multiple' => 'true', 'size' => 7, 'prompt'=>Yii::t('backend', 'Select Exclude Codes')]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?=
    // Usage with ActiveForm and model
    //change here: need to add image_path attribute from another table and add square bracket after image_path[] for multiple file upload.
    $form->field($model, 'main_img')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            //change here: below line is added just to hide upload button. Its up to you to add this code or not.
            'showUpload' => false
        ],
    ])->label(Yii::t('backend','Image'));
    ?>

    <?php
    if (!empty($model->publish_date)) {
        $model->publish_date = date('d.m.Y', strtotime($model->publish_date));
    }
    ?>
    <?php
    //datapicker language issue;
    $language = Yii::$app->language;
    if ($language == 'en') {
        $language = 'en-GB';
    }
    ?>
    <?= $form->field($model, 'publish_date')->widget(\yii\widgets\MaskedInput::className(), [
        'clientOptions' => ['alias' =>  'dd.mm.yyyy'],
    ])//->hint(Yii::t('frontend', 'Example') . ': 03.11.1981')
        ->widget(
        DatePicker::className(), [
        'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'minView' => 1,
        ],
        'language' => $language,
    ]);
    ?>

    <?= $form->field($model, 'is_active')->checkbox(['label' => Yii::t('backend','Active')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
$js = /*<<<JS
ImageToShortname = function(str) {
    var finalString = str,
    url,
    urlRegex =  /<img.*?src="([^">]*\/([^">]*?))".*?>/g,
    imgRegex = /<img.*?.*?>/;

    while ( url = urlRegex.exec( str ) ) {
        var urlString = url[1],
        currentShortname = "";
            urlString = urlString.substr(urlString.lastIndexOf('/') + 1).split('.')[0];
            
            for (var shortname in emojione.emojioneList) {
                if (emojione.emojioneList[shortname].indexOf(urlString.toLowerCase()) != -1) {
                    currentShortname = shortname;
                }
            }
            finalString = finalString.replace(imgRegex, currentShortname);
    }
    return finalString;
}
        
$('body').on('beforeSubmit', 'form#{$form->getId()}', function () {
 
    var form = $(this);

    //$(".redactor-editor").each(function() {
    //    var newHtml = ImageToShortname($(this).html());
    //    $(this).html(newHtml);
    //    $(this).next('textarea').html(newHtml);
    //});

    if (form.find('.has-error').length) {
        return false;
    }
form.submit();
    return false; // form does not get submitted
 
});
JS;*/
$js = 'jQuery(document).ready(function(){
            emojione.imagePathPNG = "' . Yii::$app->urlManagerFrontEnd->getBaseUrl() . '/images/emoji/apple/'
    . '"});' ;


$this->registerJs($js, yii\web\View::POS_READY);
?>
<?php /*
$this->registerJs("

$('#cmspages-title_en').keyup(function(){
    if ($('#cmspages-identifier').val() == '') {
        console.log('it alsow workds!');
        identifier = $(this).val();
        identifier=identifier.replace(' ','-');
        console.log(identifier);
        $('#cmspages-identifier').val(identifier);
    }
});
", yii\web\View::POS_READY , 'my-options');
*/?>