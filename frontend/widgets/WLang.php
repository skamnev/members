<?php
namespace frontend\widgets;

use Yii;
use frontend\models\Lang;
use \yii\bootstrap\Widget;

class WLang extends Widget
{
    private static $_labels;

    private static $_isError;

    private static $items;

    public function init(){
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
    }

    static public function getItems() {

        $route = Yii::$app->controller->route;
        //$defaultLanguage = Lang::getDefaultLang();
        $appLanguage = Yii::$app->language;

        //$appLanguage = $defaultLanguage->getAttribute('url');
        $params = $_GET;
        self::$_isError = $route === Yii::$app->errorHandler->errorAction;

        array_unshift($params, '/'.$route);

        $languages = Lang::find()->all();

        foreach ($languages as $language) {
            if (
                $language->getAttribute('url')===$appLanguage
            ) {
                continue;   // Exclude the current language
            }
            $params['language'] = $language->getAttribute('url');

            self::$items[] = [
                'label' => self::label($language->getAttribute('url')),
                'url' => $params,
            ];
        }

        return self::$items;
    }

    public function run() {
        //print_r($this->items);
        //return $this->items;
    }

    public static function label($code)
    {
        $languages = Lang::find()->all();

        if (self::$_labels===null) {
            self::$_labels = array();
            foreach ($languages as $language) {
                self::$_labels[$language->getAttribute('url')] = Yii::t('frontend',$language->getAttribute('name'));

            }
        }

        return isset(self::$_labels[$code]) ? self::$_labels[$code] : null;
    }
}