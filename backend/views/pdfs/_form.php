<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;
use yii\bootstrap\Modal;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Pdfs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pdfs-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?php
    $language_items = [];

    foreach($languages as $key => $language) {

        $field = $form->field($model, "name_$language->url")->textInput(['maxlength' => true])->label('Name');

        if ($languageDefault->url == $language->url) {
            $field = $form->field($model, "name")->textInput(['maxlength' => true]);
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


    <?=
    // Usage with ActiveForm and model
    //change here: need to add image_path attribute from another table and add square bracket after image_path[] for multiple file upload.
    $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'pdf/*'],
        'pluginOptions' => [
            'previewFileType' => 'image',
            //change here: below line is added just to hide upload button. Its up to you to add this code or not.
            'showUpload' => false
        ],
    ])->label(Yii::t('backend','PDF File'));
    ?>

    <?= Html::a($model->file, $model->getUploadedFileUrl('file'), array('target' => '_blank')) ?>
    <br/><br/>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'id')->hiddenInput() ?>

    <?= $form->field($model, 'is_active')->checkbox(['label' => Yii::t('backend','Active')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord):?>
        <div id="rules-main-wrapper" style="<?= $rulesDataProvider?'':'display: none;'?>">

            <h2><?= Html::encode(Yii::t('backend', 'Rules')) ?></h2>


            <div id="statusMsg"></div>
            <p>
                <?php
                Modal::begin([
                    'toggleButton' => [
                        'label' => '<i class="glyphicon glyphicon-plus"></i> Add New Rule',
                        'class' => 'btn btn-success add-new-rule-btn'
                    ],
                    'header' => '<h4 class="modal-title">Add New Rule</h4>',
                    'closeButton' => [
                        'label' => 'x',
                        'class' => 'btn btn-danger btn-sm pull-right',
                        'id' => 'create-modal-close'
                    ],
                    'id' => 'create-modal',
                    'size' => 'modal-lg',
                ]);

                $rulesModel->pdf_id = $model->id;

                echo $this->render('/pdfs-rules/create', ['model' => $rulesModel, 'languages' => $languages,'languageDefault' => $languageDefault]);
                Modal::end();
                ?>
            </p>
            <div id="rules-wrapper">
                <?php if ($rulesDataProvider):?>
                    <div class="pdfs-rules">
                        <?= GridView::widget([
                            'dataProvider' => $rulesDataProvider,
                            'pager' => [],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'pdfQuestions.title',
                                [
                                    'label' => Yii::t('backend', 'Answers'),
                                    'attribute' => '',
                                    'format' => 'html',
                                    'value' => function($model) {
                                        $codes = '';
                                        $optionsArray = $model->options_id;
                                        foreach($optionsArray as $itemModel) {
                                            $model->options_id = $itemModel;
                                            $answersModel = $model->getPdfOptions();
                                            if (!empty($answersModel)) {
                                                $codes .= '<div>' . $answersModel->title .
                                                            (empty($answersModel->description)?'':" ('$answersModel->description')") .
                                                        '<div>';
                                            }
                                        }

                                        if (!empty($codes)) {
                                            return $codes;
                                        }
                                        return null;
                                    }
                                ],
                                'is_active:boolean',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                    'headerOptions' => ['width' => '20%', 'class' => 'activity-view-link',],
                                    'contentOptions' => ['class' => 'padding-left-5px'],
                                    'controller' => 'pdfs-rules',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', [
                                                'class' => 'activity-update-link',
                                                'title' => Yii::t('backend', 'Update Rule'),
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
                'header' => '<h4 class="modal-title">Update Rule</h4>',
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
