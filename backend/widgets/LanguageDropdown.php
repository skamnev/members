<?php
namespace backend\widgets;

use backend\models\Lang;
use Yii;
use yii\bootstrap\Dropdown;

class LanguageDropdown extends Dropdown
{
    private static $_labels;

    private $_isError;

    public function init()
    {
        $route = Yii::$app->controller->route;
        //$defaultLanguage = Lang::getDefaultLang();
        $appLanguage = Yii::$app->language;;

        //$appLanguage = $defaultLanguage->getAttribute('url');
        $params = $_GET;
        $this->_isError = $route === Yii::$app->errorHandler->errorAction;

        array_unshift($params, '/'.$route);

        $languages = Lang::find()->all();

        foreach ($languages as $language) {
            if (
                $language===$appLanguage
            ) {
                continue;   // Exclude the current language
            }
            $params['language'] = $language->getAttribute('url');

            $this->items[] = [
                'label' => self::label($language->getAttribute('url')),
                'url' => $params,
            ];
        }
        parent::init();
    }

    public function run()
    {
        // Only show this widget if we're not on the error page
        if ($this->_isError) {
            return '';
        } else {
            return parent::run();
        }
    }

    public static function label($code)
    {
        $languages = Lang::find()->all();

        if (self::$_labels===null) {
            self::$_labels = array();
            foreach ($languages as $language) {
                self::$_labels[$language->getAttribute('url')] = Yii::t('backend', $language->getAttribute('name'));

            }
        }

        return isset(self::$_labels[$code]) ? self::$_labels[$code] : null;
    }
}