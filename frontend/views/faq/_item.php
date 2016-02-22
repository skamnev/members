<?php
use yii\helpers\Html;
?>

<div class="pages-item">

    <hr/>
    <strong><?= Html::a($model->title, ["faq/$category->id/" . ($model->identifier?$model->identifier:$model->id)], ['class' => '']) ?></strong>

    <?php if ($model->content && false):?>
        <p><?=$model->content;?></p>
    <?php endif;?>
</div>