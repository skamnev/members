<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Videos */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Videos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <br/>
    <?php
        $video_url = $model->external_url;
        $internalVideo = $model->getUploadedFileUrl('file');
        if ($internalVideo) {
            $video_url = $internalVideo;
        }
    ?>
    <?= \kato\VideojsWidget::widget([
        'options' => [
            'class' => 'video-js vjs-default-skin vjs-big-play-centered',
            //'poster' => 'http://vjs.zencdn.net/v/oceans.png',
            'controls' => true,
            'preload' => 'auto',
            'width' => '50%',
            'height' => '400',
            'data-setup' => '{ "plugins" : { "resolutionSelector" : { "default_res" : "720" } } }',
        ],
        'tags' => [
            'source' => [
                ['src' => $video_url, 'type' => 'video/mp4', 'data-res' => '360'],
                ['src' => $video_url, 'type' => 'video/mp4', 'data-res' => '720'],
            ],
        ],
        'multipleResolutions' => true,
    ]); ?>
    
</div>
