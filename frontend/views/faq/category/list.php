<?php
use yii\helpers\Html;
?>

<div class="faqs-category-item">
    <hr/>
    <div>
        <?= Html::a($model->title, ['faq/category/' . ($model->id)], ['class' => '']) ?>
        <div style="margin-left: 25px;">
            <?php $faq_list = $model->getCmsFaqList($model->id);?>
            <?php if ($faq_list->count() > 0):?>
                <ul>
                <?php foreach($faq_list->limit(3)->all() as $faq):?>
                    <li><strong><?= Html::a($faq->title, ["faq/$model->id/" . ($faq->identifier?$faq->identifier:$faq->id)], ['class' => '']) ?></strong></li>
                <?php endforeach;?>
                </ul>
            <?php endif;?>
        </div>
    </div>
</div>