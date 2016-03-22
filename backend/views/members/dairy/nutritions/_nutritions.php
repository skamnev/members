<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use backend\models\DiaryNutrition;

$dairyDate = date('m-d-Y', strtotime($model->created_at));

$dataProvider = new ActiveDataProvider([
    'query' => DiaryNutrition::find()->where(['=', "DATE_FORMAT(created_at,'%m-%d-%Y')", $dairyDate])
                                    ->andWhere(['member_id' => $model->member_id])
                                    ->orderBy('created_at ASC'),
]);

?>

<div class="dairy-item">
    
    <div id="statusMsg"></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => $dairyDate == date('m-d-Y')?Yii::t('frontend', 'Today'):$dairyDate,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [ 
                'attribute' =>'created_at',
                'label' => Yii::t('frontend', 'Time'),
                'format' => ['date', 'php:H:i'],
            ],
            [ 
                'attribute' =>'value',
                //'type' => 'ntext',
                'label' => Yii::t('frontend', 'Meal'),
            ],

            [
                'attribute' => 'comment',
                'format' => 'raw',
                'value' => function($data) {
                    return '<div id="nutrition-comment-' . $data->id . '">' . $data->comment . '</div>';
                }
            ],
                                    
            [
                'class' => 'yii\grid\ActionColumn',
                //'visible' => false,
                'template' => '{update}',
                'headerOptions' => ['width' => '20%', 'class' => 'activity-view-link',],
                'contentOptions' => ['class' => 'padding-left-5px'],
                'controller' => 'members',
                    'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', [
                            'class' => 'activity-update-link',
                            'title' => Yii::t('backend', 'Update Comment'),
                            'data-toggle' => 'modal',
                            'data-href' => '../members/update-nutrition-comment',
                            'data-target' => '#activity-modal',
                            'data-id' => $key,
                            'data-pjax' => '0',

                        ]);
                    },
                ],
            ],
        
        ],
    ]); ?>
    
    
</div>