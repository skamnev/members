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
                ['src' => $model->getUploadedFileUrl('file'), 'type' => 'video/mp4', 'data-res' => '360'],
                //['src' => 'http://localhost/localhost.ilives/current_html/members/backend/web/media/videos/2/8CHINS.mp4', 'type' => 'video/mp4', 'data-res' => '720'],
            ],
        ],
        'multipleResolutions' => true,
    ]); ?>
    
</div>
