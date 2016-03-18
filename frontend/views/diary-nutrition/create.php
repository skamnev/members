<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\DiaryNutrition */

$this->title = Yii::t('frontend', 'Create Diary Nutrition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Diary Nutritions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diary-nutrition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
