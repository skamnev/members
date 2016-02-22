<?php
namespace frontend\widgets;

use Yii;
use \yii\bootstrap\Widget;
use \frontend\models\SearchCmsFaq;

class FaqSearch extends Widget
{
   public static $items;

    public function init()
    {
        parent::init();
        if (self::$items === null) {
            self::$items = getItems;
        }
    }

    public function run()
    {
        return $this->getForm();
    }
    
    public function getForm(){
        $model = new \yii\base\DynamicModel(['search_text']);
        $model->addRule(['search_text'], 'string', ['max' => 128]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // do what you want 
        }
        
        return $this->render('faqsearch/form', [
            'model' => $model
        ]);
    }

    static public function getItems() {
        $searchModel = new SearchCmsFaq();
        return $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    }
}