jQuery(document).ready(function($){
    $('select').change(function(){
        el_control = $(this).parent().next().find('.form-other-control.form-control');
        if ($(this).val() == '-1' && el_control.is( ":hidden" )) {
            el_control.slideDown();
        } else {
            el_control.slideUp();
        }
    });
});