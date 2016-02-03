<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MappingQuestionsCodes */

$this->title = Yii::t('backend', 'Create Questions Codes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mapping Questions Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mapping-questions-codes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'languageDefault' => $languageDefault
    ]) ?>

</div>
