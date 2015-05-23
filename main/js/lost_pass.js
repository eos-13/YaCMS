jQuery(document).ready(function()
{
    if (jQuery('#password').length)
    {
        jQuery("#password").valid();
    }
    if (jQuery('#lost_pass_form').length)
    {
        jQuery('#lost_pass_form').validate();
    }
});