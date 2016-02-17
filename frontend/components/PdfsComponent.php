<?php

namespace frontend\components;

use backend\models\Pdfs;
use backend\models\PdfsRules;
use frontend\models\MembersQuestionsAnswers;
use Yii;
use yii\base\Component;

/**
 * This component will do:
 * - retrieve pdfs by member
 */
class PdfsComponent extends Component {

    private $pdfs;

    public function getAvailablePDFs($member) {
        $pdfs = Pdfs::find()->where(['is_active' => 1])->orderBy('order, name');

        foreach ($pdfs->all() as $_pdf) {
            $pdf_rules_match_count = $this->getPdfRule($member->id, $_pdf->id);
            if ($pdf_rules_match_count) {
                $this->pdfs[] = $_pdf;
            }
        }

        return $this->pdfs;
    }
    
    public function getAvailablePdfById($member, $pdfId) {
        $pdf = Pdfs::findOne(['is_active' => 1, 'id' => $pdfId]);
        
        $pdf_rules_match_count = $this->getPdfRule($member->id, $pdfId);
        if ($pdf_rules_match_count) {
            return $pdf;
        }
        
        return false;
    }
    
    private function getPdfRule($memberId, $pdfId) {
        $pdf_rules = PdfsRules::find()->where(['is_active' => 1, 'pdf_id' => $pdfId]);

        $memberAnswers = MembersQuestionsAnswers::find()->where(['member_id' => $memberId]);

        foreach($pdf_rules->all() as $rule) {
            $memberAnswers->andWhere(['in', 'option_id', $rule->options_id]);
        }

        return $memberAnswers->count();
    }
}