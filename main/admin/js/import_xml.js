jQuery(document).ready(function(){
    jQuery('button').button();
});

jQuery(document).ready(function()
{
    jQuery(window).bind('resize', function()
    {
        var width = jQuery(".admin-wrapper").width() - 3;
            jQuery("#table-import").attr('width',width);
            jQuery("#table-import").attr('max-width',width);
    }).trigger('resize');
});