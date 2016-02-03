<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestionsType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mapping-questions-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

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

    <?= $form->field($model, 'has_options')->checkbox() ?>

    <?= $form->field($model, 'has_other_field')->checkbox() ?>

    <div class="form-group">
        <?= Html::a( 'Back', ['fields-types/index'], ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']); ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
