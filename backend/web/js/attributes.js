/**
 * Created by sergeykamnev on 10/5/15.
 */
jQuery(document).ready(function($){
    $('#membersattributes-type_id').change(function(){
        $.ajax({
            url: 'get-options',
            type: 'post',
            data: {attribute_id: $('#membersattributes-id').val(),type_id: $(this).val()},
            success: function (result) {
                if (result.status && result.options == null) {
                    $('#options-main-wrapper').css('display', 'none');
                    $('#options-wrapper').empty();
                } else {
                    $('#options-main-wrapper').css('display', 'block');
                    $('#options-wrapper').empty();
                    $('#options-wrapper').append(result);
                }
                $.fn.activateUpdateLinks();
            }
        });
    });

    $.fn.activateUpdateLinks = function() {
        $(".activity-update-link").click(function() {
            $.get(
                '../members-attributes-to-option/update',
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