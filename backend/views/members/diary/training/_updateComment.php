<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DiaryNutrition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="diary-training-update-form">
    
     <?php $form = ActiveForm::begin([
        'action' => ['members/update-training-comment?id=' . $model->id],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'id' => 'update-nutrition-comment-form'
    ]); ?>

    <div class="form-group valign-top"></div>
    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group valign-top">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('frontend', 'Create') : Yii::t('frontend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs('
    $("#update-nutrition-comment-form").on("beforeSubmit", function(){

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
                $("#training-comment-" + result.attributes["id"]).html(result.attributes["comment"]);
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