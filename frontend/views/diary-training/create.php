<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\DiaryTraining */

$this->title = Yii::t('frontend', 'Create Diary Training');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Diary Trainings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diary-training-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
