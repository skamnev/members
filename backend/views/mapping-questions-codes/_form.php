<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestionsCodes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mapping-questions-codes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?php
    $language_items = [];

    foreach($languages as $key => $language) {

        $field = $form->field($model, "description_$language->url")->textInput(['maxlength' => true])->label('Description');

        if ($languageDefault->url == $language->url) {
            $field = $form->field($model, "description")->textInput(['maxlength' => true]);
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

    <?= $form->field($model, 'is_group')->checkbox() ?>

    <div class="form-group">
        <?= Html::a( 'Back', ['mapping-questions-codes/index'], ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']); ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
