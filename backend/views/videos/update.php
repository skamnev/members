<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Videos */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Video',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Videos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update: {name}', [
    'name' => $model->title,
]);
?>
<div class="videos-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
    ]) ?>
</div>
