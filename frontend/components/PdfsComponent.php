<?php

namespace frontend\components;

use backend\models\Pdfs;
use backend\models\PdfsRules;
use frontend\models\MembersQuestionsAnswers;
use Yii;
use yii\base\Component;
use backend\models\GeneralSettings;

/**
 * This component will do:
 * - retrieve pdfs by member
 */
class PdfsComponent extends Component {

    private $pdfs;

    public function getAvailablePDFs($member) {
        $pdfs = Pdfs::find()->where(['is_active' => 1])->orderBy('order, name');

        foreach ($pdfs->all() as $_pdf) {
            $pdf_rules_match_count = $this->getPdfRule($member->id, $_pdf);
            if ($pdf_rules_match_count) {
                $this->pdfs[] = $_pdf;
            }
        }

        return $this->pdfs;
    }
    
    public function getAvailablePdfById($member, $pdfId) {
        $pdf = Pdfs::findOne(['is_active' => 1, 'id' => $pdfId]);
        
        $pdf_rules_match_count = $this->getPdfRule($member->id, $pdf);
        if ($pdf_rules_match_count) {
            return $pdf;
        }
        
        return false;
    }
    
    private function getPdfRule($memberId, $pdf) {
        $pdf_rules = PdfsRules::find()->where(['is_active' => 1, 'pdf_id' => $pdf->id]);
        $pdfsDelay = Yii::$app->PdfsComponent->getPdfDelaySettings() * 3600;
        
        if ($pdf_rules->count() > 0) {
            $memberAnswers = MembersQuestionsAnswers::find()->where(['member_id' => $memberId]);

            foreach($pdf_rules->all() as $rule) {
                $progressUpdateTime = strtotime(Yii::$app->MappingComponent->getCategoryProgressDate($rule->category_id));
                $diffTime = time() - $progressUpdateTime;
                
                if ($diffTime < $pdfsDelay) {
                    return false;
                }

                $memberAnswers->andWhere(['in', 'option_id', $rule->options_id]);
            }

            return $memberAnswers->count();
        } else {
            $progressUpdateTime = strtotime(Yii::$app->user->identity->created_at);
            $diffTime = time() - $progressUpdateTime;

            if ($diffTime < $pdfsDelay) {
                return false;
            } else {
                return true;
            }
        }
            
        
    }
    
    public function getPdfDelaySettings() {
        $settingsModel = GeneralSettings::findOne(['name' => 'pdfs_availability_delay']);
        
        return $settingsModel->value;
    }
}