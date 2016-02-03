<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MappingCategories */

$this->title = Yii::t('backend', 'Create Mapping Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mapping Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mapping-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
