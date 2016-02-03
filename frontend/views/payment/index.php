<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Payment';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend', 'Please complete payment before access');?></p>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'customer_firstName'); ?>
    <?= $form->field($model, 'customer_lastName'); ?>
    <?= $form->field($model, 'customer_email')->textInput(['value'=> !Yii::$app->user->isGuest?Yii::$app->user->identity->email: $model->customer_email]); ?>


    <?= $form->field($model, 'creditCard_name'); ?>
    <?= $form->field($model, 'creditCard_number'); ?>
    <?= $form->field($model, 'creditCard_cvv'); ?>
    <?= $form->field($model, 'creditCard_expirationDate')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99/9999',
    ]) ?>

    <div class="form-group">
        <div class="total">
            <div class="final-price">
                <div class="description small-5 columns">
                    <?= Yii::t('frontend', 'Price per month');?>
                </div>
                <div class="small-7 columns">
                    <span class="amount">
                        <?php if (!empty($paymentPlan['description'])): ?>
                            <?= $paymentPlan['description']?>
                        <?php else: ?>
                            kr&nbsp;199,00
                        <?php endif;?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <? //= $form->field($model, 'amount')->hiddenInput(); ?>
    <?= \yii\helpers\Html::submitButton()?>
    <?php ActiveForm::end(); ?>

</div>
