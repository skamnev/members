<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\models\Lang;
use frontend\widgets\WLang;
use frontend\widgets\MappingWidget;

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

    if (\frontend\models\User::isActive() || true) {
        $menuItems = [
            ['label' => Yii::t('frontend', 'My Page'),
                'items' => [
                    ['label' => Yii::t('frontend','Weight Tracker'), 'url' => ['/members/weight-tracker']],
                    ['label' => Yii::t('frontend','Meal Plan'), 'url' => ['/members/meal-plan']],
                ]],
            ['label' => Yii::t('frontend', 'PDFs'), 'url' => ['/members/pdfs']],
            ['label' => Yii::t('frontend', 'Recipes'), 'url' => ['/recipes/categories']],
            ['label' => Yii::t('frontend', 'Articles'), 'url' => ['/articles/categories']],
            ['label' => Yii::t('frontend', 'FAQ'), 'url' => ['/faq/categories']],
            //['label' => Yii::t('frontend', 'Contact'), 'url' => ['/site/contact']],
        ];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('frontend', 'Signup'), 'url' => ['/site/signup']];
        $menuItems[] = ['label' => Yii::t('frontend', 'Login'), 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => Yii::t('frontend','Logout ({name})', ['name' => Yii::$app->user->identity->email]),
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    $menuItems[] = [
        'label' => Yii::t('frontend', Lang::getLangByUrl(Yii::$app->language)->name),
        'items' => WLang::getItems(),
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?php //skamnev - debug information?>
        <div id="membersCodesList" style="color: red;">
            <br/>
            <?php
                $memberGroupCode = \frontend\components\MembersCodesComponent::getMemberGroupCode();
                $memberAnswersCodes = \frontend\components\MembersCodesComponent::getMemberCodes(true);
            ?>
            <div>
                <h5>Members Group Code: <b><?php echo empty($memberGroupCode)?'Not Set':$memberGroupCode;?></b></h5>
            </div>
            <div>
                <?php if (!empty($memberAnswersCodes)):?>
                    <h5>Members Codes List:</h5>
                    <ul>
                        <?php foreach ($memberAnswersCodes as $answer_code): ?>
                            <li><?php echo $answer_code?></li>
                        <?php endforeach;?>
                    </ul>
                <?php else: ?>
                    <h5>Members Codes List:
                    <b>Not Defined</b></h5>
                <?php endif; ?>
            </div>
        </div>
        <?php //skamnev - debug information END?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        <?= MappingWidget::widget(['mappingCategoryId' => Yii::$app->MappingComponent->updateMappingCategory]) ?>
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
