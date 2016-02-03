<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\models\MembersQuestionsAnswers;
use backend\models\MappingQuestionsToOptions;
use backend\models\MembersAttributesAnswers;
use backend\models\MembersAttributesToOptions;
use dosamigos\datepicker\DatePicker;

$this->title = $pageModel->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?/* display page heading if exists */?>
    <?php if (!empty($pageModel->content_heading)): ?>
        <h1><?= Html::encode($pageModel->content_heading) ?></h1>
    <?php endif; ?>

    <?/* display page content if exists */?>
    <?php if (!empty($pageModel->content)): ?>
    <p><?= $pageModel->content;?></p>
    <?php endif; ?>


    <div id="mapping-wrapper">
        <?php if ($questionsModel) :?>
            <?php $form = ActiveForm::begin(); ?>

            <?//= $form->errorSummary($questionsAnswers); ?>
            <?//= $form->errorSummary($attributesAnswers); ?>

            <?/* output all attributes related tot the mapping category */?>
            <?php
                $member_id = Yii::$app->getUser()->id;

                foreach ($attributesModel as $model) {
                    $value = $option_id = '';
                    if ($answersModel = MembersAttributesAnswers::findOne(['attribute_id' => $model->id, 'member_id' => $member_id])) {
                        $value = $answersModel->value;
                        $option_id =  $answersModel->option_id;
                    }

                    if ($model->fieldsTypes[0]->has_options) { //if question has options display a dropdown
                        echo $form->field($attributesAnswers, "value_$model->id")
                            ->dropDownList(ArrayHelper::map(MembersAttributesToOptions::findAll(
                                ['attribute_id' => $model->id]), 'id', 'label'),
                                ['prompt'=>Yii::t('frontend','-- Select --'),
                                    'options' => [$option_id => ['Selected'=>'selected'],]
                                ])
                            ->label($model->label);
                    } else { // else if question does not have options display a text field
                        if ($model->fieldsTypes[0]->title == 'Date Picker') { //@TODO replace datepicker with some database constatnt value;
                            if (!empty($value)) {
                                $attributesAnswers->{"value_$model->id"} = date('F d Y', $value);
                            }
                            echo $form->field($attributesAnswers, "value_$model->id")->widget(
                                DatePicker::className(), [
                                // inline too, not bad
                                'inline' => true,
                                // modify template for custom rendering
                                'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'MM dd yyyy'
                                ],
                                'language' => Yii::$app->language,
                            ]);
                        } else {
                            echo $form->field($attributesAnswers, "value_$model->id")->textInput(['value' => $value])->label($model->label);
                        }
                    }
                }
            ?>

            <?/* output all questions related tot the mapping category */?>
            <?php
                foreach ($questionsModel as $model) {
                    $value = $option_id = '';
                    if ($answersModel = MembersQuestionsAnswers::findOne(['question_id' => $model->id, 'member_id' => $member_id])) {
                        $value = $answersModel->value;
                        $option_id =  $answersModel->option_id;
                    }

                    if ($model->fieldsTypes[0]->has_options) { //if question has options display a dropdown
                        echo $form->field($questionsAnswers, "value_$model->id")
                            ->dropDownList(ArrayHelper::map(MappingQuestionsToOptions::findAll(
                                ['question_id' => $model->id]), 'id', 'title'),
                                ['prompt'=>Yii::t('frontend','-- Select --'),
                                    'options' => [$option_id => ['Selected'=>'selected'],]
                                ])
                            ->label($model->title);

                        //$this->registerJs("$('form').yiiActiveForm('updateMessages', {
    //'MembersQuestionsAnswers[32][value]': ['I don\'t like it!']
//}, true);");
                    } else { // else if question does not have options display a text field
                        echo $form->field($questionsAnswers, "value_$model->id")->textInput(['value' => $value]);
                    }

                }
            ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        <?php endif;?>
    </div>
</div>
