<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MembersAttributesToOptions */

$this->title = Yii::t('backend', 'Update {modelTitle}', [
    'modelTitle' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Members Attributes To Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="members-attributes-to-options-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
