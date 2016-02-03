<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Members */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $tab_items[] = [
        'label' => Yii::t('backend', 'General Information'),
        'content' => /*'<p>' . GridView::widget([
                'dataProvider' => $attributesAnswers,
                'summary'=>'',
                'showFooter'=>false,
                'showHeader' => false,

                'columns' => [
                    [
                        'attribute' => 'label',
                        'contentOptions' => ['style' => 'font-weight:bold;']
                    ],
                    [
                        'header' => Yii::t('backend', 'Answer'),
                        'headerOptions' => ['width' => '80'],
                        'attribute'=>'attributesAnswers.value',
                        'value' => function($model) {
                            if ($model->type_id == 7) {
                                return Yii::$app->formatter->asDate($model->attributesAnswers->value);
                            } else {
                                return $model->attributesAnswers->value;
                            }
                        },
                        'options' => ['width' => '39%']
                    ],
                ],
            ]) . '</p>' .*/
            '<p>' . DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'firstname',
                    'lastname',
                    'username',
                    'email:email',
                    'braintree_customer_id',
                    'weight',
                    'height',
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => \backend\models\Members::getStatusLabel($model->status),
                    ],
                    'created_at:date',
                ],
            ]) . '</p>',
        //'active' => $key==0
    ];

    $questionItems = [];
    foreach ($categoriesModel as $categoryModel) {
        // get questions answers
        $questionsAnswers = new ActiveDataProvider([
            'query' => $categoryModel->getQuestions()->with('questionsAnswers'),
        ]);

        $questionItems[] = [
            'label' => $categoryModel->name,
            'content' => '<p>' . GridView::widget([
                    'dataProvider' => $questionsAnswers,
                    'summary'=>'',
                    'showFooter'=>false,
                    'showHeader' => false,
                    'columns' => [
                        'title',
                        [
                            'header' => Yii::t('backend', 'Answer'),
                            'attribute'=>'questionsAnswers.option_id',
                            'value' => function($model) {
                                if ($model->questionsAnswers->option_id > 0) {
                                    return \backend\models\MappingQuestionsToOptions::findOne($model->questionsAnswers->option_id)->title;
                                } else {
                                    if (!empty($model->questionsAnswers->value)) {
                                        return $model->questionsAnswers->value;
                                    } else if (!empty($model->questionsAnswers->other)) {
                                        return $model->questionsAnswers->other;
                                    }
                                }
                            }
                        ]
                    ],
                ]) . '</p>',
            //'active' => true
        ];

    }

    if (count($questionItems) > 0) {
        $tab_items[] = [
            'label' => Yii::t('backend', 'Mapping'),
            'content' => '<p>' . Tabs::widget([
                    'items' => $questionItems
                ]) . '</p>',
            //'active' => true
        ];
    }

    $tab_items[] = [
        'label' => Yii::t('backend', 'Weight Tracker'),
        'content' => '<p>' . GridView::widget([
                'dataProvider' => $weightTracker,
                'summary'=>'',
                'showFooter'=>false,
                'showHeader' => true,
                'columns' => [
                    'value',
                    'created_at:date',
                ],
            ]) . '</p>',
        //'active' => true
    ];

    echo Tabs::widget([
        'items' => $tab_items
    ]);
    ?>

</div>
