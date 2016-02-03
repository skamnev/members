<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MembersWeightTracker */

$this->title = 'Create Members Weight Tracker';
$this->params['breadcrumbs'][] = ['label' => 'Members Weight Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-weight-tracker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
