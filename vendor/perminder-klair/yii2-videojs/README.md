VideoJs Player for Yii2
=======================
VideoJs Player for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist perminder-klair/yii2-videojs "dev-master"
```

or add

```
"perminder-klair/yii2-videojs": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \kato\VideojsWidget::widget([
    'options' => [
        'class' => 'video-js vjs-default-skin vjs-big-play-centered',
        'poster' => 'http://video-js.zencoder.com/oceans-clip.png',
        'controls' => true,
        'preload' => 'auto',
        'width' => '970',
        'height' => '400',
        'data-setup' => '{ "plugins" : { "resolutionSelector" : { "default_res" : "720" } } }',
    ],
    'tags' => [
        'source' => [
            ['src' => 'http://video-js.zencoder.com/oceans-clip.mp4', 'type' => 'video/mp4', 'data-res' => '360'],
            ['src' => 'http://video-js.zencoder.com/oceans-clip.mp4', 'type' => 'video/mp4', 'data-res' => '720'],
        ],
    ],
    'multipleResolutions' => true,
]); ?>
```