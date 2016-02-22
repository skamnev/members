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
    }

    public function run()
    {
        return $this->getForm();
    }
    
    public function getForm(){
        $model = new \yii\base\DynamicModel(['search_text']);
        
        $model->addRule(['search_text'], 'string', ['max' => 128]);
        $model->addRule(['search_text'], 'required');
        
        $model->load(Yii::$app->request->get());
        
        return $this->render('faqsearch/form', [
            'model' => $model
        ]);
    }
}