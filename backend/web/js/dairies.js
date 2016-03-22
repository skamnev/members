/**
 * Created by sergeykamnev on 10/5/15.
 */
jQuery(document).ready(function($){
    $.fn.activateUpdateLinks = function() {
        $(".activity-update-link").click(function() {
            $.get(
                $(this).attr('data-href'),
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
});