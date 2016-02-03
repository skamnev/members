<?php

namespace frontend\components;

use backend\models\MappingQuestionsCodes;
use backend\models\MembersQuestionsAnswers;
use Yii;
use yii\base\Component;

/**
 * This component will do:
 * - retrive member codes
 * - filter codes in the article/recipe content
 */
class MembersCodesComponent extends Component
{
    static $groupedCodesArray = [];
    static $memberAnswersCodesSqlFilters = [];

    public static function getMemberGroupCode() {

        $groupedCodesArray = self::getGroupedCodes();
        //get code id with max sum value
        list($memberGroupCode) = array_keys($groupedCodesArray['countCodes'], max($groupedCodesArray['countCodes']));

        return $memberGroupCode;
    }

    public static function getGroupedCodes () {
        if (empty(self::$groupedCodesArray)) {
            $groupedCodes = MappingQuestionsCodes::findAll(['is_group' => 1]);
            foreach ($groupedCodes as $groupedCode) {
                $memberAnswers = MembersQuestionsAnswers::find()->innerJoinWith('questionOption')->where(['code_id' => $groupedCode->id])->count();
                self::$groupedCodesArray['countCodes'][$groupedCode->id] = $memberAnswers;
                self::$groupedCodesArray['codes'][] = $groupedCode->id;
            }
        }

        return self::$groupedCodesArray;
    }

    public static function getMemberCodes($with_grouped = 0) {
        $groupedCodesArray = self::getGroupedCodes();
        /**
         * Get all answers excluding group codes
         */
        if ($with_grouped) {
            $memberAnswers = MembersQuestionsAnswers::find()->innerJoinWith('questionOption')->asArray()->all();
        } else {
            $memberAnswers = MembersQuestionsAnswers::find()->innerJoinWith('questionOption')->where(['not in', 'code_id', $groupedCodesArray['codes']])->asArray()->all();
        }
        /**
         * Run throw answers to build where arrays
         */
        $memberAnswersCodes = [];
        foreach ($memberAnswers as $answer) {
            $answer_codes = explode(',', $answer['questionOption']['code_id']);
            $codes = MappingQuestionsCodes::find()->where(['id' => $answer_codes])->asArray()->all();
            foreach($codes as $code) {
                $memberAnswersCodes[$code['id']] = $code['code'];
            }

        }
        return $memberAnswersCodes;
    }

    public static function getMemberCodesSqlFilter() {
        if (empty(self::$memberAnswersCodesSqlFilters)) {
            $groupedCodesArray = self::getGroupedCodes();
            /**
             * Get all answers excluding group codes
             */
            $memberAnswers = MembersQuestionsAnswers::find()->innerJoinWith('questionOption')->where(['not in', 'code_id', $groupedCodesArray['codes']])->all();
            /**
             * Run throw answers to build where arrays
             */
            foreach ($memberAnswers as $answer) {
                foreach ($answer->questionOption->code_id as $code_id) {
                    //build include codes ids array
                    self::$memberAnswersCodesSqlFilters['include'][] = ['like', 'code_id', "[$code_id]"];
                    //build exclude codes ids array
                    self::$memberAnswersCodesSqlFilters['exclude'][] = ['not like', 'no_code_id', "[$code_id]"];
                }
            }
        }

        return self::$memberAnswersCodesSqlFilters;
    }

    public static function filterOffMemberCodes($content) {
        $memberCodes = self::getMemberCodes(true);

        foreach ($memberCodes as $memberCode) {
            $content = preg_replace("/\[$memberCode\](.*)\[\/$memberCode\]/", "$1", $content);
        }

        //find codes left after replacement to remove it at all
        preg_match_all("/(?<=\[\/).*?(?=\])/", $content, $codes_left);

        foreach ($codes_left[0] as $code) {
            $content = preg_replace("/\[$code\](.*)\[\/$code\]/", "$2", $content);
        }

        return $content;
    }
}