<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MembersAttributesToOptions */

$this->title = Yii::t('backend', 'Create New Options');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Members Attributes To Options'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-attributes-to-options-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
