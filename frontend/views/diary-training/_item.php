<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use frontend\models\DiaryTraining;

$diaryDate = date('m-d-Y', strtotime($model->created_at));

$dataProvider = new ActiveDataProvider([
    'query' => DiaryTraining::find()->where(['=', "DATE_FORMAT(created_at,'%m-%d-%Y')", $diaryDate])
                                    ->andWhere(['member_id' => Yii::$app->getUser()->id])
                                    ->orderBy('created_at ASC'),
]);

?>

<div class="diary-item">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => $diaryDate == date('m-d-Y')?Yii::t('frontend', 'Today'):$diaryDate,
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
                'class' => 'yii\grid\ActionColumn',
                'visible' => false,
                'template' => ''
            ],
        
        ],
    ]); ?>
</div>