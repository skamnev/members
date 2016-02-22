<?php
use yii\helpers\Html;
?>

<div class="faqs-category-item">
    <hr/>

    <div>
        <?= Html::a($model->title, ['articles/category/' . ($model->id)], ['class' => '']) ?>
    </div>
</div>