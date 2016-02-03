<?php
use yii\helpers\Html;
?>

<div class="recipes-item">

    <hr/>
    <strong></b><?=$model->content_heading?$model->content_heading:$model->title;?></strong>

    <?php if ($model->content):?>
        <p><?=$model->content;?></p>
    <?php endif;?>
    <div>
        <?= Html::a(Yii::t('frontend', 'More...'), ['recipe/' . ($model->identifier?$model->identifier:$model->id)], ['class' => '']) ?>
    </div>
</div>