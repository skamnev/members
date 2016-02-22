<?php
use yii\helpers\Html;
?>
<div id="faq-search">
    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Search'), ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>