<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Weight Tracker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'My Page')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-weight-tracker-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::img($userModel->getThumbFileUrl('avatar', 'thumb')) ?>
    </p>
    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($userModel); ?>
    
    <?=
    // Usage with ActiveForm and model
    //change here: need to add image_path attribute from another table and add square bracket after image_path[] for multiple file upload.
    $form->field($userModel, 'avatar')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => true,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' =>  'Select Photo',
            'uploadUrl' => '',
        ],
        'options' => ['accept' => 'image/*'],
    ])->label(Yii::t('backend','Profile Photo'));
    ?>
    <?php ActiveForm::end(); ?>

    <h3><?= Yii::t('frontend', 'My Starting Weight: {weight} lbs', ['weight' => $startingWeight]) ?></h3>

    <h3><?= Yii::t('frontend', 'My Current Weight: {weight} lbs', ['weight' => $currentWeight]) ?></h3>


    <?php if ($allowAddWeight):?>
        <p>
            <?= Html::a(Yii::t('frontend', 'Add Weight'), ['weight-tracker-add'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <h3><?= Yii::t('frontend', 'My Weight Entries') ?></h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'created_at',
                'format' => 'text',
                'label' => Yii::t('frontend', 'Date'),
            ],
            [
                'attribute' => 'value',
                'format' => 'text',
                'label' => Yii::t('frontend', 'Weight'),
            ],
        ],
        'emptyText' => Yii::t('frontend', 'No results found.'),
        'summary' => Yii::t('frontend', 'Showing {begin}-{end} of {count} item(s).'),
    ]); ?>

</div>
