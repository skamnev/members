<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\PdfsRules */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pdfs-rules-form">

    <?php $form = ActiveForm::begin([
        'action' => ['pdfs-rules/' . ($model->isNewRecord ? 'create?pdf_id=' . $model->pdf_id : 'update?id=' . $model->id)],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'id' => $model->isNewRecord ? 'create-rules-form' : 'update-rules-form'
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?php
        $category_options = [
            'prompt'=>Yii::t('backend', 'Select Categories'),
                'onchange' =>
                    '$.post("' . \yii\helpers\Url::to(['pdfs-rules/get-questions-list' ]) .  '",{"id" : $(this).val()}, function(data) {
                                                            $("select#pdfsrules-question_id").html(data);
                                                        })'
            ];

        $question_options = [
            'prompt'=>Yii::t('backend', 'Select Question'),
            'onchange' =>
                '$.post("' . \yii\helpers\Url::to(['pdfs-rules/get-options-list' ]) .  '",{"id" : $(this).val()}, function(data) {
                                                            $("select#pdfsrules-options_id").html(data);
                                                        })'
        ];

        $answers_options = [
            'prompt'=>Yii::t('backend', 'Select Answers'),
            'multiple' => 'true',
            'size' => 7,
        ];

        $questions_condition['fields_type.has_options'] = 1;

        if (!empty($model->category_id)) {
            $category_options['options'] = [$model->category_id => ['Selected'=>'selected']];

            $questions_condition['category_id'] = $model->category_id;
            $question_options['options'] = [$model->question_id => ['Selected'=>'selected']];

            $answers_condition['question_id'] = $model->question_id;
            $answers_options['options'] = [$model->options_id => ['Selected'=>'selected']];
        }

        $question_items = array();
        $options_items = array();
    
        if (!$model->isNewRecord) {
            $question_items = \yii\helpers\ArrayHelper::map(\backend\models\MappingQuestions::find()->joinWith('fieldsTypes')->where($questions_condition)->all(), 'id', 'title');
            $options_items = \yii\helpers\ArrayHelper::map(\backend\models\MappingQuestionsToOptions::find()->where($answers_condition)->all(), 'id', 'title');
        }

    ?>

    <?= $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\MappingCategories::find()->all(), 'id', 'name'),
                                                            $category_options
                                                        )->label(Yii::t('backend', 'Select Categories')) ?>

    <?= $form->field($model, 'question_id')->dropDownList($question_items, $question_options)->label(Yii::t('backend', 'Select Question')) ?>

    <?= $form->field($model, 'options_id')->dropDownList($options_items, $answers_options)->label(Yii::t('backend', 'Select Answers')) ?>

    <?= $form->field($model, 'progress')->textInput() ?>

    <?= $form->field($model, 'is_active')->checkbox(['label' => Yii::t('backend','Active')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
if ($model->isNewRecord) {
    $this->registerJs('
    $("#create-rules-form").on("beforeSubmit", function(){

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
                $("#create-rules-form").trigger("reset");
                $.fn.updateRulesGrid();
            })
            .fail(function() {
    console.log("server error");
});
        return false;
    });');
} else {
    $this->registerJs('
    $("#update-rules-form").on("beforeSubmit", function(){

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
                $("#update-rules-form").trigger("reset");
                $.fn.updateRulesGrid();
            })
            .fail(function() {
    console.log("server error");
});
        return false;
    });');
}
