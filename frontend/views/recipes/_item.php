<?php
use yii\helpers\Html;
?>

<div class="recipes-item">

    <hr/>
    <strong><?= Html::a($model->content_heading?$model->content_heading:$model->title, ["recipe/$category->id/" . ($model->identifier?$model->identifier:$model->id)], ['class' => '']) ?></strong>

    <?php if ($model->content):?>
        <p><?=$model->content;?></p>
    <?php endif;?>
</div>