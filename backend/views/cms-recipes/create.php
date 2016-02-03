<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsRecipes */

$this->title = Yii::t('backend', 'Create Recipe');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Recipes Categories'), 'url' => ['cms-pages-categories/index']];
if (!empty($categoryModel->title)) {
    $this->params['breadcrumbs'][] = ['label' => $categoryModel->title, 'url' => ['cms-recipes-categories/view', 'category_id' => $categoryModel->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-recipes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'categoryModel' => $categoryModel,
    ]) ?>

</div>
