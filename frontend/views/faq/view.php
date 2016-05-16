<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsFaq */
$_prefix = '_' . Yii::$app->language;

$this->title = empty($model->{'title' . $_prefix})?$model->title:$model->{'title' . $_prefix};
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'FAQ Categories'), 'url' => ['faq/categories']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['faq/category/' . $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-faq-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->{'content_' . Yii::$app->language}):?>
        <div>
            <p><?= $model->{'content_' . Yii::$app->language} ?></p>
        </div>
    <?php elseif ($model->content): ?>
        <div>
            <p><?= $model->content ?></p>
        </div>
    <?php endif;?>
</div>
