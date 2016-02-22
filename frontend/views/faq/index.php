<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Articles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Articles Categories'), 'url' => ['articles/categories']];
$this->params['breadcrumbs'][] = $category->title;
?>
<div class="cms-pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo \frontend\widgets\FaqSearch::widget();?>
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'viewParams' => ['category' => $category],
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
