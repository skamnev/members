<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DiaryTraining */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="diary-training-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-inline'],
    ]); ?>

    <div class="form-group valign-top"></div>
    <?= $form->field($model, 'value')->textInput(['maxlength' => true])->label(false) ?>

    <div class="form-group valign-top">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('frontend', 'Create') : Yii::t('frontend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
