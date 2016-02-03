<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MembersWeightTracker */

$this->title = 'Add Weight';
$this->params['breadcrumbs'][] = ['label' => 'My Page'];
$this->params['breadcrumbs'][] = ['label' => 'Weight Trackers', 'url' => ['weight-tracker-add']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-weight-tracker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
