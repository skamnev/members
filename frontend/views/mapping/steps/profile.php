<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

?>
<div class="mapping-profile">
    <?php
    $model = $steps['profileModel'];
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <?php
    //if (!empty($model->birthday)) {
        //echo $model->birthday;
        //$model->birthday = date('m/d/Y', strtotime($model->birthday));
    //}
    ?>
    <?= $form->field($model, 'birthday')->widget(\yii\widgets\MaskedInput::className(), [
        'clientOptions' => ['alias' =>  'dd.mm.yyyy'],
    ])->hint(Yii::t('frontend', 'Example') . ': 03.11.1981');/*->widget(
        DatePicker::className(), [
        // inline too, not bad
        //'inline' => false,
        // modify template for custom rendering
        //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'mm/dd/yyyy',
            'minView' => 1,
        ],
        'language' => Yii::$app->language,
    ]);//;*/
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>