<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use backend\models\FieldsTypes;
use yii\grid\GridView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\MembersAttributes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-attributes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?php
    $language_items = [];

    foreach($languages as $key => $language) {

        $field = $form->field($model, "label_$language->url")->textInput(['maxlength' => true])->label('Label');

        if ($languageDefault->url == $language->url) {
            $field = $form->field($model, "label")->textInput(['maxlength' => true]);
        }

        $language_items[] = [
            'label' => Yii::t('backend',$language->name),
            'content' => "<p>$field</p>",
            'active' => $key==0
        ];
    }

    echo Tabs::widget([
        'items' => $language_items
    ]);
    ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false) ?>

    <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map(FieldsTypes::find()->all(), 'id', 'title'), ['prompt'=>'Select Option Type']) ?>

    <?= $form->field($model, 'is_required')->checkbox(['label' => Yii::t('backend','Required')]); ?>

    <?= $form->field($model, 'is_active')->checkbox(['label' => Yii::t('backend','Active')]); ?>

    <div class="form-group">
        <?= Html::a( 'Back', ['members-attributes/index'], ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']); ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord):?>
        <div id="options-main-wrapper" style="<?= $optionsDataProvider?'':'display: none;'?>">

            <h2><?= Html::encode(Yii::t('backend', 'Options')) ?></h2>


            <div id="statusMsg"></div>
            <p>
                <?php
                Modal::begin([
                    'toggleButton' => [
                        'label' => '<i class="glyphicon glyphicon-plus"></i> Add New Option',
                        'class' => 'btn btn-success add-new-option-btn'
                    ],
                    'header' => '<h4 class="modal-title">Add New Option</h4>',
                    'closeButton' => [
                        'label' => 'x',
                        'class' => 'btn btn-danger btn-sm pull-right',
                        'id' => 'create-modal-close'
                    ],
                    'id' => 'create-modal',
                    'size' => 'modal-lg',
                ]);

                $optionsModel->attribute_id = $model->id;

                echo $this->render('/members-attributes-to-option/create', ['model' => $optionsModel, 'languages' => $languages,'languageDefault' => $languageDefault]);
                Modal::end();
                ?>
            </p>
            <div id="options-wrapper">
                <?php if ($optionsDataProvider):?>
                    <div class="attributes-options">
                        <?= GridView::widget([
                            'dataProvider' => $optionsDataProvider,
                            'pager' => [],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'id',
                                'title',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                    'headerOptions' => ['width' => '20%', 'class' => 'activity-view-link',],
                                    'contentOptions' => ['class' => 'padding-left-5px'],
                                    'controller' => 'members-attributes-to-option',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', [
                                                'class' => 'activity-update-link',
                                                'title' => Yii::t('backend', 'Update Option'),
                                                'data-toggle' => 'modal',
                                                'data-target' => '#activity-modal',
                                                'data-id' => $key,
                                                'data-pjax' => '0',

                                            ]);
                                        },
                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php Modal::begin([
                'id' => 'activity-modal',
                'header' => '<h4 class="modal-title">Update Option</h4>',
                'closeButton' => [
                    'label' => 'x',
                    'class' => 'btn btn-danger btn-sm pull-right',
                    'id' => 'activity-modal-close'
                ],
                //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
            ]); ?>

            <div class="well">


            </div>


            <?php Modal::end(); ?>

        </div>
    <?php endif;?>
</div>
