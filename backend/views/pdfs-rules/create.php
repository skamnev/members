<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PdfsRules */

$this->title = Yii::t('backend', 'Create Pdfs Rules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pdfs Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pdfs-rules-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'mappingCategories' => $mappingCategories,
    ]) ?>

</div>
