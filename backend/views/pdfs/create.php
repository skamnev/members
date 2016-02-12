<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Pdfs */

$this->title = Yii::t('backend', 'Create Pdfs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pdfs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web/js/questions.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

?>
<div class="pdfs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault,
        'rulesModel' => $rulesModel,
        'mappingCategories' => $mappingCategories,
    ]) ?>

</div>
