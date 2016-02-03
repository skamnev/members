<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Weight Tracker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'My Page')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-weight-tracker-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3><?= Yii::t('frontend', 'My Starting Weight: {weight} lbs', ['weight' => $startingWeight]) ?></h3>

    <h3><?= Yii::t('frontend', 'My Current Weight: {weight} lbs', ['weight' => $currentWeight]) ?></h3>


    <?php if ($allowAddWeight):?>
        <p>
            <?= Html::a(Yii::t('frontend', 'Add Weight'), ['weight-tracker-add'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <h3><?= Yii::t('frontend', 'My Weight Entries') ?></h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'created_at',
                'format' => 'text',
                'label' => Yii::t('frontend', 'Date'),
            ],
            [
                'attribute' => 'value',
                'format' => 'text',
                'label' => Yii::t('frontend', 'Weight'),
            ],
        ],
        'emptyText' => Yii::t('frontend', 'No results found.'),
        'summary' => Yii::t('frontend', 'Showing {begin}-{end} of {count} item(s).'),
    ]); ?>

</div>
