<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\GeneralSettings */
/* @var $form yii\widgets\ActiveForm */
$plan_items = ArrayHelper::map(\backend\models\CmsMealPlan::find()->all(), 'id', 'title');
$update_freq_items = [];

for ($week=1; $week<=7; $week++) {
    $update_freq_items[$week] = date('D', mktime(0,0,0,0,$week,1970));
}
?>

<div class="general-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelPlanId, 'value')->dropDownList($plan_items, ['name' => 'values[mealplan_current_id]'])->label('Current Meal Plan') ?>
    
    <?= $form->field($modelPlanFreq, 'value')->dropDownList($update_freq_items, ['name' => 'values[mealplan_update_freq]'])->label('Meal Plan Update Frequency') ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
