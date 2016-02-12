<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Pdfs */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'PDFs',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'PDFs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web/js/rules.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

?>

<div class="pdfs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'rulesModel' => $rulesModel,
        'rulesDataProvider' => $rulesDataProvider,
        'mappingCategories' => $mappingCategories,
    ]) ?>

</div>
