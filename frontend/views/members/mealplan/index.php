<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsPages */
$_prefix = '_' . Yii::$app->language;

$this->title = empty($mealplanModel->{'title' . $_prefix})?$mealplanModel->title:$mealplanModel->{'title' . $_prefix};
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-pages-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($mealplanModel->{'content_' . Yii::$app->language}):?>
        <div>
            <p><?= $mealplanModel->{'content_' . Yii::$app->language} ?></p>
        </div>
    <?php elseif ($mealplanModel->content): ?>
        <div>
            <p><?= $mealplanModel->content ?></p>
        </div>
    <?php endif;?>
</div>
