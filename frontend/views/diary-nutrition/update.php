<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\DiaryNutrition */

$this->title = Yii::t('frontend', 'Update {modelClass}: ', [
    'modelClass' => 'Diary Nutrition',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Diary Nutritions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="diary-nutrition-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
