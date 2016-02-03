<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\models\MembersQuestionsAnswers;
use backend\models\MappingQuestionsToOptions;

$this->title = $categoryModel->name;
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web/js/mapping.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div id="mapping-wrapper">
        <?php $form = ActiveForm::begin(); ?>

        <?//= $form->errorSummary($questionsAnswers); ?>

        <?/* output all attributes related tot the mapping category */?>
        <?php
        $member_id = Yii::$app->getUser()->id;
        ?>

        <?/* output all questions related tot the mapping category */?>
        <?php
        if(Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
        }
        foreach ($questionsModel as $model) {
            $value = $option_id = '';
            if ($answersModel = MembersQuestionsAnswers::findOne(['question_id' => $model->id, 'member_id' => $member_id])) {
                $value = $answersModel->value;
                $option_id =  $answersModel->option_id;
            } else if(!empty($postData['MembersQuestionsAnswers']["value_$model->id"])) {
                $value = $option_id =  $postData['MembersQuestionsAnswers']["value_$model->id"];
            }

            if ($model->fieldsTypes[0]->has_options) { //if question has options display a dropdown
                $optionsArray = ArrayHelper::map(MappingQuestionsToOptions::findAll(
                    ['question_id' => $model->id]), 'id', 'title');

                if ($model->fieldsTypes[0]->has_other_field && $model->has_other) {
                    $optionsArray[-1] = Yii::t('frontend', 'Other');
                }

                echo $form->field($questionsAnswers, "value_$model->id")
                    ->dropDownList($optionsArray,
                        ['prompt'=>Yii::t('frontend','-- Select --'),
                            'options' => [$option_id => ['Selected'=>'selected'],]
                        ])
                    ->label($model->title);
            } else { // else if question does not have options display a text field
                echo $form->field($questionsAnswers, "value_$model->id")->textInput(['value' => $value]);
            }

            if ($model->fieldsTypes[0]->has_other_field && $model->has_other) {
                if ($answersModel) {
                    $options = ['value' => $answersModel->other];
                }
                if ($option_id > 0) {
                    $options = ['style' => 'display: none;'];
                } else {
                    $options = ['style' => 'display: block;'];
                }

                if ($model->fieldsTypes[0]->has_options) {
                    $options['class'] = 'form-other-control form-control';
                }

                echo $form->field($questionsAnswers, "other_$model->id")->textInput($options)->label(false);
            }
        }
        ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('frontend', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
