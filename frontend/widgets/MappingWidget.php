<?php
namespace frontend\widgets;

use backend\models\MappingCategories;
use Yii;
use \yii\bootstrap\Widget;

class MappingWidget extends Widget {
    public $mappingCategoryId;

    public function init(){
        if($this->mappingCategoryId==false && !empty(Yii::$app->MappingComponent->updateMappingCategory)){
            $this->mappingCategoryId = Yii::$app->MappingComponent->updateMappingCategory;
        }
        parent::init();
    }

    public function run(){
        $category = MappingCategories::findOne($this->mappingCategoryId);
        $route = Yii::$app->controller->route;
        $request_id = Yii::$app->request->get('id');
        $ignore_route = array('site/changepassword', 'mapping/index', 'payment/index');

        if (($route == 'mapping/update-answer' && $request_id == $this->mappingCategoryId) || in_array($route, $ignore_route) ) {
            return false;
        } else if ($category) {
            return $this->render('mapping/modal', ['category' => $category]);
        }
    }
}