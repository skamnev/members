<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\models\CmsPages;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Pages');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pages Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Page'), ['cms-pages/create', 'category_id' => $category_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->is_active == 0){
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            array(
                'attribute'=>'is_active',
                'filter'=>Html::activeDropDownList($searchModel, 'is_active', CmsPages::dropdownActive(), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'All')]),
                'format' => 'boolean',
            ),
            'sort_order',
            //'created_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'urlCreator'=>function($action, $model, $key, $index) use ($category_id) {
                    return ['cms-pages/' . $action,'category_id'=>$category_id, 'id'=>$model['id']];
                },
            ],
        ],
    ]); ?>

</div>