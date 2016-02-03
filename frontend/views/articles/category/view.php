<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Articles Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-recipes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'list',
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{pager}\n{items}\n{summary}",
        'emptyText' => Yii::t('frontend', 'No results found.'),
        'summary' => Yii::t('frontend', 'Showing {begin}-{end} of {count} item(s).'),
    ]); ?>

</div>
