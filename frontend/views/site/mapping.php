<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\Tabs;

$this->title = $pageModel->title;
$this->params['breadcrumbs'][] = $this->title;
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
        <?php if ($categoryModel) :?>
            <?php foreach($categoryModel as $key => $category):?>


            <?php
                $tab_items[] = [
                    'label' => $category->name,
                    'content' => '<p>' . $this->render('mapping_form', ['category' => $category, 'questionsAnswers' => $questionsAnswers]) . '</p>',
                    'active' => $key==0
                ];
            ?>

            <?php endforeach;?>

            <?php
                echo Tabs::widget([
                    'items' => $tab_items
                ]);
            ?>
        <?php endif;?>
    </div>
</div>
