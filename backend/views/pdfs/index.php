<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PdfsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'PDFs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdfs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Add PDF'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'order',
            'file:ntext',
            array(
                'attribute'=>'is_active',
                'filter'=>Html::activeDropDownList($searchModel, 'is_active', \backend\models\Pdfs::dropdownActive(), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'All')]),
                //'filter'=>Html::dropDownList(ArrayHelper::map(\backend\models\MappingCategories::find()->all(), 'id', 'name'), ['prompt'=>Yii::t('backend', 'Select Page')]),
                'format' => 'boolean',
            ),
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
