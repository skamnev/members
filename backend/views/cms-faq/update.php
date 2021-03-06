<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsFaq */

$this->title = Yii::t('backend', 'Update Faq: ') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Faq Categories'), 'url' => ['cms-faq-categories/index']];
$this->params['breadcrumbs'][] = ['label' => $categoryModel->title, 'url' => ['cms-faq-categories/view', 'category_id' => $categoryModel->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update') . ' ' . $model->title;
?>
<div class="cms-faq-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('backend', 'Preview'), ["preview", 'category_id' => $categoryModel->id, 'id' => $model->id], ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
        <?//= Html::a(Yii::t('backend', 'Preview'), ["../../" . Yii::$app->language ."/faq/$categoryModel->id/" . ($model->identifier?$model->identifier:$model->id)], ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
    </p>
    <p>
        <?php $frontend_url = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['/article/' . $categoryModel->id . '/' . ($model->identifier?$model->identifier:$model->id)]);?>
        <?//= yii\bootstrap\Html::textInput('frontend_link', $frontend_url, ['maxlength' => true, 'style'=>'width: 100%', 'disabled' => 'disabled']);?>
    </p>
    
    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'categoryModel' => $categoryModel,
    ]) ?>

</div>
