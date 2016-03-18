<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\DiaryTraining */

$this->title = Yii::t('frontend', 'Update {modelClass}: ', [
    'modelClass' => 'Diary Training',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Diary Trainings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="diary-training-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
