<?php

namespace frontend\components;

use backend\models\MembersAttributes;
use Yii;
use yii\validators\Validator;
use backend\models\MappingQuestions;

class MappingValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $attributeArray = explode('_', $attribute);

        if (count($attributeArray) > 1) {
            list($value, $id) = $attributeArray;
            $questionModel = MappingQuestions::findOne(['id' => $id]);
            $attributesModel = MembersAttributes::findOne(['id' => $id]);
            if (($questionModel && $questionModel->is_required) && empty($model->$attribute)) {
                $message = "'$questionModel->title' " . Yii::t('errors', 'can not be empty!');

                $this->addError($model, $attribute, $message);
            } else if (($attributesModel && $attributesModel->is_required) && empty($model->$attribute)) {
                $message = "'$attributesModel->label' " . Yii::t('errors', 'can not be empty!');

                $this->addError($model, $attribute, $message);
            }
        }
    }
}
