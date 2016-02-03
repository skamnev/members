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
            $pdf_rules = PdfsRules::find()->where(['is_active' => 1, 'pdf_id' => $_pdf->id]);

            $memberAnswers = MembersQuestionsAnswers::find()->where(['member_id' => $member->id]);

            foreach($pdf_rules->all() as $rule) {
                $memberAnswers->andWhere(['in', 'option_id', $rule->options_id]);
            }

            if ($memberAnswers->count()) {
                $this->pdfs[] = $_pdf;
            }
        }

        return $this->pdfs;
    }
}