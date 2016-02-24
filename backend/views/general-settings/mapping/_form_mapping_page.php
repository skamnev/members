<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\CmsPages;

/* @var $this yii\web\View */
/* @var $model backend\models\GeneralSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="general-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, '[]value')->dropDownList(ArrayHelper::map(CmsPages::find()->all(), 'id', 'title'), ['prompt'=>Yii::t('backend', 'Select Page')])->label(false) ?>

    <?= $form->field($model, '[]id')->hiddenInput()->label(false); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
