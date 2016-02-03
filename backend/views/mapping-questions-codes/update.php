<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestionsCodes */


$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mapping Questions Codes',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mapping Questions Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mapping-questions-codes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
