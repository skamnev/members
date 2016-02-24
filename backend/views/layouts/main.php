<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\widgets\WLang;
use backend\models\Lang;

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

use webvimark\modules\UserManagement\components\GhostMenu;
use webvimark\modules\UserManagement\UserManagementModule;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Improving LIVES',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => Yii::t('backend', 'Users'), 'url' => ['/user-management/user/index']],
        ['label' => Yii::t('backend', 'Members'), 'url' => ['/members/index']],
        ['label' => Yii::t('backend', 'PDFs'), 'url' => ['/pdfs']],
        ['label' => Yii::t('backend', 'Videos'), 'url' => ['/videos']],
        ['label' => Yii::t('backend', 'Mapping'),
            'items' => [
                ['label' => Yii::t('backend','Categories'), 'url' => ['/mapping-categories/index']],
                ['label' => Yii::t('backend','Question Codes'), 'url' => ['/mapping-questions-codes/index']],
            ]],
        ['label' => Yii::t('backend', 'CMS'),
            'items' => [
                ['label' => Yii::t('backend','Pages'), 'url' => ['/cms-pages-categories/index']],
                ['label' => Yii::t('backend','Recipes'), 'url' => ['/cms-recipes-categories/index']],
                ['label' => Yii::t('backend','FAQ'), 'url' => ['/cms-faq-categories/index']],
            ]],
        ['label' => Yii::t('backend', 'Settings'),
            'items' => [
                ['label' => Yii::t('backend','General'), 'url' => ['/general-settings/index']],
                ['label' => Yii::t('backend','Languages'), 'url' => ['/lang/index']],
                ['label' => Yii::t('backend','Fields Types'), 'url' => ['/fields-types/index']],
                ['label' => Yii::t('backend','Weight Tracker'), 'url' => ['/general-settings/weight-tracker']],
                //['label' => Yii::t('backend','Mapping Page'), 'url' => ['/general-settings/mapping-page']],
                //['label' => Yii::t('backend','Attributes'), 'url' => ['/members-attributes/index']],
            ]],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }

    $menuItems[] = [
        'label' => Yii::t('backend', Lang::getLangByUrl(Yii::$app->language)->name),
        'items' => WLang::getItems(),
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Improving LIVES <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
