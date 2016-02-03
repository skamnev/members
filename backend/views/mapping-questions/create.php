<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestions */

$this->title = Yii::t('backend', 'Create Question');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mapping Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => \backend\models\MappingCategories::findOne($category_id)->name . ' ' . Yii::t('backend', 'Questions'), 'url' => ['mapping-categories/view', 'id' => $category_id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web/js/questions.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

?>
<div class="mapping-questions-create">

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
