/**
 * Created by sergeykamnev on 10/5/15.
 */
jQuery(document).ready(function($){
    console.log($('#pdfs-id').val());
    $.fn.updateRulesGrid = function(){
        $.ajax({
            url: 'get-rules',
            type: 'post',
            data: {pdf_id: $('#pdfs-id').val()},
            success: function (result) {
                if (result.status && result.rules == null) {
                    $('#rules-main-wrapper').css('display', 'none');
                    $('#rules-wrapper').empty();
                } else {
                    $('#rules-main-wrapper').css('display', 'block');
                    $('#rules-wrapper').empty();
                    $('#rules-wrapper').append(result);
                }
                $.fn.activateUpdateLinks();
            }
        });
    };

    $.fn.activateUpdateLinks = function() {
        $(".activity-update-link").click(function() {
            $.get(
                '../pdfs-rules/update',
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