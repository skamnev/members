<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use frontend\widgets\FaqSearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Search');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'FAQ'), 'url' => ['faq/categories']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-faq-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo FaqSearch::widget();?>
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        //'viewParams' => ['category' => $category],
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
