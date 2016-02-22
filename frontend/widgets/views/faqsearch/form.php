<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div id="faq-search">
    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'get',
    'layout' => 'inline',]); ?>
        <?= $form->field($model, "search_text")->textInput(['maxlength' => true]);?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('frontend', 'Search'), ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>