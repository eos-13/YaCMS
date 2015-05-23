//jQuery(document).ready(function(){
//    jQuery( "button.dropdown-toggle" )
//    .button()
//    .click(function(e) {
//        e.preventDefault();
//        var that = jQuery(this);
//        var menu = jQuery( this ).parent().find('.dropdown-menu').show().position({
//            my: "left top",
//            at: "left bottom",
//            of: this
//        });
//        jQuery( document ).one( "click", function(e) {
//            e.preventDefault();
//            if (jQuery(e.target).hasClass("ui-button-text"))
//            {
//                jQuery(that).val(jQuery(e.target).text());
//                jQuery(e.target).parents('ul').find('.ui-state-highlight').each(function(){
//                    jQuery(this).removeClass('ui-state-highlight');
//                });
//                jQuery(e.target).parent().addClass('ui-state-highlight');
//            }
//            menu.hide();
//        });
//        return false;
//    })
//    .parent()
//    .buttonset()
//    .next()
//    .hide()
//    .menu();
//});
jQuery(document).ready(function(){
    jQuery( "button.dropdown-toggle" )
    .button()
    .click(function(e) {
        var that = jQuery(this);
        var menu = jQuery( this ).parent().find('.dropdown-menu').show().position({
            my: "left top",
            at: "left bottom",
            of: this
        });
        jQuery( document ).one( "click", function(e) {
            if (jQuery(e.target).hasClass("ui-button-text"))
            {
                jQuery(that).parent(".btn-group").find('input:hidden').val(jQuery(e.target).text());
                jQuery(e.target).parents('ul').find('.ui-state-highlight').each(function(){
                    jQuery(this).removeClass('ui-state-highlight');
                });
                jQuery(e.target).parent().addClass('ui-state-highlight');
            }
            menu.hide();
        });
        return false;
    })
    .parent()
    .buttonset()
    .next()
    .hide()
    .menu();
});
jQuery(document).ready(function(){
    jQuery( ".datepicker" ).datepicker( jQuery.datepicker.regional[ lang_short ] );
})