<?php
use yii\helpers\Html;
?>

<div class="faq-item">

    <hr/>
    <strong><?= Html::a($model->title?$model->title:$model->title, ['faq/' . $model->category_id[0] . '/' . ($model->identifier?$model->identifier:$model->id)], ['class' => '']) ?></strong>

    <?php if ($model->content && false):?>
        <p><?=$model->content;?></p>
    <?php endif;?>
</div>