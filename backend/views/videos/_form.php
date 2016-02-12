<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\MappingQuestionsCodes;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Videos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="videos-form">

     <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?php
    $language_items = [];
    $redactorClientOptions = [
        'imageManagerJson' => ['/redactor/upload/image-json'],
        'imageUpload' => ['/redactor/upload/image'],
        'fileUpload' => ['/redactor/upload/file'],
        'lang' => Yii::$app->language,
        'plugins' => ['clips', 'fontcolor','imagemanager']
    ];

    foreach($languages as $key => $language) {

        $field = $form->field($model, "title_$language->url")->textInput(['maxlength' => true])->label('Title');
        $field_description = $form->field($model, "description_$language->url")->widget(\yii\redactor\widgets\Redactor::className(),[
            'clientOptions' => $redactorClientOptions
        ]);

        if ($languageDefault->url == $language->url) {
            $field = $form->field($model, "title")->textInput(['maxlength' => true]);
            
            $field_description = $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className(),[
                'clientOptions' => $redactorClientOptions
            ]);
        }

        $language_items[] = [
            'label' => Yii::t('backend',$language->name),
            'content' => "<p>$field $field_description</p>",
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
    ])->label(Yii::t('backend','Video File'));
    ?>
    
    <div class="row">
        <div class="col-md-6">
            <?= \kato\VideojsWidget::widget([
                'options' => [
                    'class' => 'video-js vjs-default-skin vjs-big-play-centered',
                    //'poster' => 'http://vjs.zencdn.net/v/oceans.png',
                    'controls' => true,
                    'preload' => 'auto',
                    'width' => '100%',
                    'height' => '300',
                    'data-setup' => '{ "plugins" : { "resolutionSelector" : { "default_res" : "720" } } }',
                ],
                'tags' => [
                    'source' => [
                        ['src' => $model->getUploadedFileUrl('file'), 'type' => 'video/mp4', 'data-res' => '360'],
                        //['src' => 'http://localhost/localhost.ilives/current_html/members/backend/web/media/videos/2/8CHINS.mp4', 'type' => 'video/mp4', 'data-res' => '720'],
                    ],
                ],
                'multipleResolutions' => true,
            ]); ?>
        </div>
        <div class="col-md-6">
            
            <?php
                $video = \kato\VideojsWidget::widget([
                    'options' => [
                        'class' => 'video-js vjs-default-skin vjs-big-play-centered',
                        //'poster' => 'http://vjs.zencdn.net/v/oceans.png',
                        'controls' => true,
                        'preload' => 'auto',
                        'width' => '80%',
                        'height' => 'auto',
                        'data-setup' => '{ "plugins" : { "resolutionSelector" : { "default_res" : "720" } } }',
                    ],
                    'tags' => [
                        'source' => [
                            ['src' => $model->getUploadedFileUrl('file'), 'type' => 'video/mp4', 'data-res' => '360'],
                            ['src' => $model->getUploadedFileUrl('file'), 'type' => 'video/mp4', 'data-res' => '720'],
                        ],
                    ],
                    'multipleResolutions' => true,
                ]);
                
                echo Html::label(Yii::t('backend', 'Embed Html Code'), 'videos_html_code');
                echo Html::textarea('videos_html_code', $video,['rows' => 10, 'style' => 'width: 100%']);
            ?>
        </div>
    </div>
    <br/>
    <?= Html::a($model->file, $model->getUploadedFileUrl('file'), array('target' => '_blank')) ?>
    <br/><br/>
    
    <?= $form->field($model, 'status')->checkbox(['label' => Yii::t('backend','Active')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
