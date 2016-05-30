<?php

namespace frontend\components;
use Yii;
use yii\base\Component;
use backend\models\CmsMealPlan;
use backend\models\GeneralSettings;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MealPlanComponent extends Component
{
    public function init() {
        parent::init();
    }
    
    public function updateMealPlanId() {
        
        if ($this->isTimeToUpdate()) {
            $currentMealPlanId = GeneralSettings::findOne(['name' => 'mealplan_current_id']);
            
            $newMealPlanId = (int)$this->getNextMealPlanId($currentMealPlanId->value);
            
            if (is_numeric($newMealPlanId)) {
                $modelPlanId = GeneralSettings::findOne(['name' => 'mealplan_current_id']);
                $modelPlanId->value = $newMealPlanId;
                if ($modelPlanId->save()) {
                    $modelPlanFreq = GeneralSettings::findOne(['name' => 'mealplan_update_time']);
                    if ($modelPlanFreq) {
                        $modelPlanFreq->value = strtotime(date('Y-m-d'));
                        $modelPlanFreq->save();
                    }
                }
            }
        }
    }
    
    private function getNextMealPlanId($currentMealPlanId) {
        //1463954400
            $result = CmsMealPlan::find()->where(['>', 'id', $currentMealPlanId])->orderBy('sort_order, created_at ASC')->one();
            
            if (!$result){
                $result = CmsMealPlan::find()->orderBy('sort_order, created_at ASC')->one();
            }
            
            return $result->id;
    }
    
    private function isTimeToUpdate() {
        $datetime = strtotime(date('Y-m-d'));
        $mealplanUpdateFreqModel = GeneralSettings::findOne(['name' => 'mealplan_update_freq']);
        
        if ($mealplanUpdateFreqModel->value == date('N', $datetime)-1) {
            
            $mealplanUpdateTimeModel = GeneralSettings::findOne(['name' => 'mealplan_update_time']);
            $mealplanUpdateDateTiem = strtotime(date('Y-m-d', $mealplanUpdateTimeModel->value));
            $dateDifference = $datetime-$mealplanUpdateDateTiem;
            
            if ($dateDifference >= 6*24*3600 || $mealplanUpdateTimeModel->value == null) {
                return true;
            }
        }
        
        return false;
    }
    private function getMealPlansList() {
        return CmsMealPlan::find()->orderBy('sort_order, created_at ASC')->asArray()->all();
    }
    
    private function getMealPlanValueByName($settingName) {
        
    }
}