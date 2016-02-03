<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;

$this->title = $pageModel->title;
$this->params['breadcrumbs'][] = $this->title;


  $this->registerCssFile('/css/mapping.css');
?>
<div class="site-login">
    <?/* display page heading if exists */?>
    <?php if (!empty($pageModel->content_heading)): ?>
        <h1><?= Html::encode($pageModel->content_heading) ?></h1>
    <?php endif; ?>

    <?/* display page content if exists */?>
    <?php if (!empty($pageModel->content)): ?>
    <p><?= $pageModel->content;?></p>
    <?php endif; ?>

    <div id="mapping-wrapper">

        <?php echo $this->render('mapping_header', ['steps' => $steps]);?>

        <?php echo $this->render('steps/' . $steps['view'], ['steps' => $steps]);?>
    </div>
</div>
