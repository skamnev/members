<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\models\CmsPagesCategories;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Pages Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-pages-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Create Page'), ['cms-pages/create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute'=>'status',
                'filter'=>Html::activeDropDownList($searchModel, 'status', CmsPagesCategories::dropdownActive(), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'All')]),
                'value'=> 'statusLabel',
            ],
            'sort_order',
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator'=>function($action, $model, $key, $index){
                    return ($action=='view')?['cms-pages-categories/' . $action,'category_id'=>$model['id']]
                        :['cms-pages-categories/' . $action,'id'=>$model['id']];
                },],
        ],
    ]); ?>

</div>
