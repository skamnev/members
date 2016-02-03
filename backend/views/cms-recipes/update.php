<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CmsRecipes */

$this->title = Yii::t('backend', 'Update Recipe:') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Recipes Categories'), 'url' => ['cms-recipes-categories/index']];
$this->params['breadcrumbs'][] = ['label' => $categoryModel->title, 'url' => ['cms-recipes-categories/view', 'category_id' => $categoryModel->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update') . ' ' . $model->title;
?>
<div class="cms-recipes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'categoryModel' => $categoryModel,
    ]) ?>

</div>
