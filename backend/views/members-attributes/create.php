<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MembersAttributes */

$this->title = Yii::t('backend', 'Create Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Members Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web/js/attributes.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);
?>
<div class="members-attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'optionsDataProvider' => $optionsDataProvider,
        'optionsModel' => $optionsModel,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
