<?php

use yii\helpers\Html;
use \backend\models\MappingCategories;
/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestions */
$title = $model->findOne($model->id)->title;
$this->title = Yii::t('backend', 'Update {modelTitle}', [
    'modelTitle' => $title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mapping Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => MappingCategories::findOne($category_id)->name . ' ' . Yii::t('backend', 'Questions'), 'url' => ['mapping-categories/view', 'id' => $category_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update') . ' ' . $title;

$this->registerJsFile(Yii::getAlias('@web/js/questions.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

?>
<div class="mapping-questions-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category_id' => $category_id,
        'optionsDataProvider' => $optionsDataProvider,
        'optionsModel' => $optionsModel,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
