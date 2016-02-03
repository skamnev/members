<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use \backend\models\MembersAttributes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$title = $category->findOne($category->id)->name;
$this->title = $title . ' ' .Yii::t('backend', 'Questions');
$this->params['breadcrumbs'][] = [ 'label' => Yii::t('backend', 'Mapping Categories'), 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mapping-questions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a( 'Back', ['mapping-categories/index'], ['class' => 'btn btn-primary']); ?>
        <?= Html::a(Yii::t('backend', 'Create Question'), ['/mapping-questions/create?id=' . $category->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'is_required:boolean',
            'is_active:boolean',
            'order',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'controller' => 'mapping-questions',
                'urlCreator'=>function($action, $model, $key, $index){
                    return ($action=='update' || $action == 'delete')?['mapping-questions/' . $action,'cat_id'=>$model['category_id'], 'id'=>$model['id']]
                        :['mapping-questions/' . $action,'id'=>$model['id']];
                },
            ]
        ],
    ]); ?>

</div>

