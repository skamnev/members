<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FieldsType */

$this->title = Yii::t('backend', 'Create Questions Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Questions Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fields-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
