<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsPages */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Articles Categories'), 'url' => ['articles/categories']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['articles/category/' . $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-pages-view">

    <h1>
        <?php if ($model->content_heading):?>
            <?= Html::encode($model->content_heading) ?>
        <?php else: ?>
            <?= Html::encode($this->title) ?>
        <?php endif; ?>
    </h1>

    <?php if ($model->content): ?>
        <div>
            <p><?= $model->content ?></p>
        </div>
    <?php endif;?>
</div>
