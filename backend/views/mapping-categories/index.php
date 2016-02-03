<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\MappingCategoriesLang;
use \backend\models\MappingCategories;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Mapping Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mapping-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            array(
                'attribute'=>'is_active',
                'filter'=>Html::activeDropDownList($searchModel, 'is_active', MappingCategories::dropdownActive(), ['class' => 'form-control', 'prompt' => Yii::t('backend', 'All')]),
                //'filter'=>Html::dropDownList(ArrayHelper::map(\backend\models\MappingCategories::find()->all(), 'id', 'name'), ['prompt'=>Yii::t('backend', 'Select Page')]),
                'format' => 'boolean',
            ),
            'duration',
            'sort_order',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
