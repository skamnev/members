<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CmsPagesCategories */

$this->title = Yii::t('backend', 'Create Pages Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pages Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-pages-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
    ]) ?>

</div>
