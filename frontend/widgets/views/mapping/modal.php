<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

?>
<?php
yii\bootstrap\Modal::begin([
    'header' => '<h3>' . Yii::t('backend', 'Update {category} answers', [
            'category' => $category->name,
        ]) . '</h3>',
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'mappingAnswersModal',
    'size' => 'modal-sm',
    'clientOptions' => ['show' => true, 'backdrop' => 'static','tabindex'=>'-1']
]);?>
<div id="answers-modalContent center-block">
    <?= Html::beginForm(['mapping/update-answer', 'id' => $category->id], 'post', ['enctype' => 'multipart/form-data']) ?>

    <?= Html::submitButton(Yii::t('frontend','Yes'), ['class' => 'btn btn-success', 'name' => 'btn-yes']) ?>
    <?= Html::submitButton(Yii::t('frontend','No'), ['class' => 'btn btn-primary', 'name' => 'btn-no']) ?>

    <?php Html::endForm(); ?>
</div>
<?yii\bootstrap\Modal::end();
?>