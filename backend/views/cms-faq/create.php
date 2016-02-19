<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsFaq */

$this->title = Yii::t('backend', 'Create FAQ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Faq Categories'), 'url' => ['cms-faq-categories/index']];
if (!empty($categoryModel->title)) {
    $this->params['breadcrumbs'][] = ['label' => $categoryModel->title, 'url' => ['cms-faq-categories/view', 'category_id' => $categoryModel->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'categoryModel' => $categoryModel,
    ]) ?>

</div>
