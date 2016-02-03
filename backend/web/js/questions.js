/**
 * Created by sergeykamnev on 10/5/15.
 */
jQuery(document).ready(function($){
    $('#mappingquestions-type_id').change(function(){
        $.ajax({
            url: 'get-options',
            type: 'post',
            data: {question_id: $('#mappingquestions-id').val(),type_id: $(this).val()},
            success: function (result) {
                if (result.other_field == null) {
                    $('.field-mappingquestions-has_other').css('display', 'none');
                } else {
                    $('.field-mappingquestions-has_other').css('display', 'block');
                }
                if (result.options == null) {
                    $('.field-mappingquestions-has_other').css('display', 'none');
                    $('#options-main-wrapper').css('display', 'none');
                    $('#options-wrapper').empty();
                } else {
                    $('.field-mappingquestions-has_other').css('display', 'block');
                    $('#options-main-wrapper').css('display', 'block');
                    $('#options-wrapper').empty();
                    $('#options-wrapper').append(result.options);
                }
                $.fn.activateUpdateLinks();
            }
        });
    });

    $.fn.activateUpdateLinks = function() {
        $(".activity-update-link").click(function() {
            $.get(
                '../mapping-questions-to-options/update',
                {
                    id: $(this).closest('tr').data('key')
                },
                function (data) {
                    $('#activity-modal .modal-body').html(data);
                    $('#activity-modal').modal();
                }
            );
        });
    }
    $.fn.activateUpdateLinks();
});