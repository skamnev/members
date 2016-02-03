<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lang */

$this->title = Yii::t('backend', 'Update Language') . ': ' . ' ' . Yii::t('backend', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="lang-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
