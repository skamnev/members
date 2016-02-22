<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Videos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Videos'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description:ntext',
            'file:ntext',
            array(
                'attribute'=>'status',
                'filter'=>Html::activeDropDownList($searchModel, 'status', \backend\models\Videos::dropdownActive(), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'All')]),
                //'filter'=>Html::dropDownList(ArrayHelper::map(\backend\models\MappingCategories::find()->all(), 'id', 'name'), ['prompt'=>Yii::t('backend', 'Select Page')]),
                'format' => 'boolean',
            ),
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}',],
        ],
    ]); ?>

</div>
