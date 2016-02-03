<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsRecipesCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-recipes-categories-form">

    <p>
        <?= Html::img($model->getThumbFileUrl('main_img', 'thumb')) ?>
    </p>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

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

    <?php
    // Usage with ActiveForm and model
    //change here: need to add image_path attribute from another table and add square bracket after image_path[] for multiple file upload.
    echo $form->field($model, 'main_img')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            //change here: below line is added just to hide upload button. Its up to you to add this code or not.
            'showUpload' => false
        ],
    ])->label(Yii::t('backend','Image'));
    ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
