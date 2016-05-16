<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsRecipes */
$_prefix = '_' . Yii::$app->language;

$this->title = empty($model->{'title' . $_prefix})?$model->title:$model->{'title' . $_prefix};
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Recipes Categories'), 'url' => ['recipes/categories']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['recipes/category/' . $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-recipes-view">
    <h1>
        <?php if ($model->{'content_heading' . $_prefix}):?>
            <?= Html::encode($model->content_heading) ?>
        <?php else: ?>
            <?= Html::encode($this->title) ?>
        <?php endif; ?>
    </h1>

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
