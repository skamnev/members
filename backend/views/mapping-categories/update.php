<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MappingCategories */
$title = $model->findOne($model->id)->name;
$this->title = Yii::t('backend', 'Update Category: {modelName}', [
    'modelName' => $title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mapping Categories'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mapping-categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
    ]) ?>

</div>
