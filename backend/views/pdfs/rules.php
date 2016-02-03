<?php

use yii\helpers\Html;
use yii\grid\GridView;

?>
    <div class="pdfs-rules">
        <?= GridView::widget([
            'dataProvider' => $rulesDataProvider,
            'pager' => [],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'pdfQuestions.title',
                'is_active:boolean',
                [
                    'label' => Yii::t('backend', 'Answers'),
                    'attribute' => '',
                    'format' => 'html',
                    'value' => function($model) {
                        $codes = '';
                        $optionsArray = $model->options_id;
                        foreach($optionsArray as $itemModel) {
                            $model->options_id = $itemModel;
                            $answersModel = $model->getPdfOptions();
                            if (!empty($answersModel)) {
                                $codes .= '<div>' . $answersModel->title .
                                    (empty($answersModel->description)?'':" ('$answersModel->description')") .
                                    '<div>';
                            }
                        }

                        if (!empty($codes)) {
                            return $codes;
                        }
                        return null;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'headerOptions' => ['width' => '20%', 'class' => 'activity-view-link',],
                    'contentOptions' => ['class' => 'padding-left-5px'],
                    'controller' => 'pdfs-rules',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', [
                                'class' => 'activity-update-link',
                                'title' => Yii::t('backend', 'Update Rule'),
                                'data-toggle' => 'modal',
                                'data-target' => '#activity-modal',
                                'data-id' => $key,
                                'data-pjax' => '0',

                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
<?php
$this->registerJs('
    $("#update-rules-form").on("beforeSubmit", function(){

        var $form = $(this);

        $.post(
            $form.attr("action"),
            $form.serialize()
        )
            .done(function(result) {

                $("#statusMsg").animate({
                    height: "toggle",
                    opacity: "toggle"
                },"medium",function() {
    $("#statusMsg").replaceWith("<div id=\"statusMsg\" class=\"alert alert-" + result.status + "\">" + result.message + "</div>");
});

                $("#activity-modal-close").trigger("click");
                $("#update-rules-form").trigger("reset");
                $.fn.updateRulesGrid();
            })
            .fail(function() {
    console.log("server error");
});
        return false;
    });');