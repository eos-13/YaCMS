jQuery(document).ready(function(){
    jQuery('button').button();
    jQuery('#checkall').button();
    jQuery('#uncheck').button();
    jQuery('#invertcheck').button();

    jQuery('#checkall').click(function(e)
    {
        e.preventDefault();
        jQuery('.cbox').prop('checked',"checked");

    });
    jQuery('#uncheck').click(function(e)
    {
        e.preventDefault();
        jQuery('.cbox').prop('checked',"");

    });
    jQuery('#invertcheck').click(function(e)
    {
        e.preventDefault();
        jQuery('.cbox').each(function()
        {
            if (jQuery(this).prop('checked'))
            {
                jQuery(this).prop('checked',"");
            } else {
                jQuery(this).prop('checked',"checked");
            }
        });

     });

});