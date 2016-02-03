<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsPages */

$this->title = Yii::t('backend', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pages Categories'), 'url' => ['cms-pages-categories/index']];
if (!empty($categoryModel->title)) {
    $this->params['breadcrumbs'][] = ['label' => $categoryModel->title, 'url' => ['cms-pages-categories/view', 'category_id' => $categoryModel->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-pages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'categoryModel' => $categoryModel,
    ]) ?>

</div>
