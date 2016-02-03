<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\MappingQuestionsCodes;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestionsToOptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mapping-questions-to-options-form">

    <?php $form = ActiveForm::begin([
        'action' => ['mapping-questions-to-options/' . ($model->isNewRecord ? 'create' : 'update?id=' . $model->id)],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'id' => $model->isNewRecord ? 'create-options-form' : 'update-options-form'
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'question_id')->hiddenInput()->label(false) ?>

    <?php
    $language_items = [];

    foreach($languages as $key => $language) {

        $field = $form->field($model, "title_$language->url")->textInput(['maxlength' => true])->label('Title');

        if ($languageDefault->url == $language->url) {
            $field = $form->field($model, "title")->textInput(['maxlength' => true]);
        }

        $language_items[] = [
            'label' => Yii::t('backend',$language->name),
            'content' => "<p>$field</p>",
            'active' => $key==0
        ];
    }

    echo Tabs::widget([
        'items' => $language_items
    ]);
    ?>

    <?= $form->field($model, 'code_id')->dropDownList(ArrayHelper::map(MappingQuestionsCodes::find()->all(), 'id', 'code'), ['multiple' => 'true', 'size' => 7, 'prompt'=>'Select Option Code']) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
if ($model->isNewRecord) {
    $this->registerJs('
    $("#create-options-form").on("beforeSubmit", function(){

        var $form = $(this);

        $.post(
            $form.attr("action"),
            $form.serialize()
        )
            .done(function(result) {

                $("#statusMsg").animate({
                    height: "toggle",
                    opacity: "toggle"
                },"medium",function() {
    $("#statusMsg").replaceWith("<div id=\"statusMsg\" class=\"alert alert-" + result.status + "\">" + result.message + "</div>");
});

                $("#create-modal-close").trigger("click");
                $("#create-options-form").trigger("reset");
                $("#mappingquestions-type_id").trigger("change");
            })
            .fail(function() {
    console.log("server error");
});
        return false;
    });');
} else {
    $this->registerJs('
    $("#update-options-form").on("beforeSubmit", function(){

        var $form = $(this);

        $.post(
            $form.attr("action"),
            $form.serialize()
        )
            .done(function(result) {

                $("#statusMsg").animate({
                    height: "toggle",
                    opacity: "toggle"
                },"medium",function() {
    $("#statusMsg").replaceWith("<div id=\"statusMsg\" class=\"alert alert-" + result.status + "\">" + result.message + "</div>");
});

                $("#activity-modal-close").trigger("click");
                $("#update-options-form").trigger("reset");
                $("#mappingquestions-type_id").trigger("change");
            })
            .fail(function() {
    console.log("server error");
});
        return false;
    });');
}