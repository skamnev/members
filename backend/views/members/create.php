<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Members */

$this->title = Yii::t('backend', 'Create Members');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
