<?php

namespace kato;

use yii\web\AssetBundle;

/**
 * Asset bundle for DropZone Widget
 */
class VideojsAsset extends AssetBundle
{
    public $multipleResolutions = false;

    public $sourcePath = '@videojs';

    public $js = [
        "bower_components/video-js/dist/video-js/video.js"
    ];

    public $css = [
        "bower_components/video-js/dist/video-js/video-js.css"
    ];

    /**
     * @var array
     */
    public $publishOptions = [
        'forceCopy' => true
    ];

    public function registerAssetFiles($view)
    {
        //if multiple resolutions enabled, init it's plugin
        if ($this->multipleResolutions) {
            $this->js[] = 'plugins/videojs-resolution-selector/video-quality-selector.js';
            $this->css[] = 'plugins/videojs-resolution-selector/button-styles.css';
        }

        parent::registerAssetFiles($view);
    }

}