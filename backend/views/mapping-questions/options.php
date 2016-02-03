<?php

use yii\helpers\Html;
use yii\grid\GridView;

?>
<div class="quesions-options">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'label' => Yii::t('backend', 'Code'),
                'attribute' => 'code_id',
                'value' => function($model) {
                    $codeModel = $model->getMappingQuestionsCodes();
                    if (!empty($codeModel)) {
                        return $codeModel->code .(empty($codeModel->description)?'':" ('$codeModel->description')");
                    }

                    return null;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => ['width' => '20%', 'class' => 'activity-view-link',],
                'contentOptions' => ['class' => 'padding-left-5px'],
                'controller' => 'mapping-questions-to-options',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', [
                            'class' => 'activity-update-link',
                            'title' => Yii::t('backend', 'Update Option'),
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
    $("#update-options-form").on("beforeSubmit", function(){

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
                $("#update-options-form").trigger("reset");
                $("#mappingquestions-type_id").trigger("change");
            })
            .fail(function() {
    console.log("server error");
});
        return false;
    });');