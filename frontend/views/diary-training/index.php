<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Diary Trainings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diary-training-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="diary-today-log">
        <h3><?= Yii::t('frontend', 'Today') ?></h3>
    
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        //'viewParams' => ['item' => $model],
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{pager}\n{items}\n{pager}",//"{pager}\n{items}\n{pager}{summary}",
        'emptyText' => Yii::t('frontend', 'No results found.'),
        'summary' => Yii::t('frontend', 'Showing {begin}-{end} of {count} item(s).'),
    ]); ?>

</div>
