<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GeneralSettings */

$this->title = Yii::t('backend', 'Set Mapping Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'General Settings')];//, 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update Mapping Page');
?>
<div class="general-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_mapping_page', [
        'model' => $model,
    ]) ?>

</div>
