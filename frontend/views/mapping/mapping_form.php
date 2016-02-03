<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\models\MembersQuestionsAnswers;
use backend\models\MappingQuestionsToOptions;

?>

<?php
$questionsModel = \backend\models\MappingQuestions::find()->where(['category_id' => $category->id, 'is_active' => 1])->with('fieldsTypes')->all();
?>
<?php $form = ActiveForm::begin(); ?>

<?//= $form->errorSummary($questionsAnswers); ?>

<?/* output all attributes related tot the mapping category */?>
<?php
$member_id = Yii::$app->getUser()->id;
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