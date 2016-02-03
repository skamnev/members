<?php
use yii\helpers\Html;
?>

<div class="recipes-category-item">
    <hr/>

    <div>
        <?= Html::a($model->title, ['recipes/category/' . ($model->id)], ['class' => '']) ?>
    </div>
</div>