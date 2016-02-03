<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MembersWeightTracker */

$this->title = 'Update Members Weight Tracker: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Members Weight Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="members-weight-tracker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
