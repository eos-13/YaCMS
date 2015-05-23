jQuery(document).ready(function(){
    jQuery("button").button();
    jQuery("select").selectmenu({ width:400 });
});
jQuery(document).ready(function()
{
    if (jQuery('#register').length)
    {

        jQuery('form#register').validate(
        {
             rules:
             {
                 name:
                 {
                     required: true,
                     rangelength: [3, 30]
                 },
                 firstname:
                 {
                     required: true,
                     rangelength: [3, 30]
                 },
                 email:
                 {
                     required: true,
                     email: true,
                     remote:
                     {
                         url: "register?action=validate",
                         type: "post",
                     }
                 },
                 login:
                 {
                     required: true,
                     rangelength: [3, 30],
                     remote:
                     {
                         url: "register?action=validate",
                         type: "post",
                     }
                 },
                 password_again:
                 {
                     equalTo: "#password",
                     required: true
                 },
                 password:
                 {
                     password: "#login",
                     required: true
                 }

             }
        });
        jQuery("#password").change(function()
        {
            jQuery.validator.passwordRating.messages = {
                    "similar-to-username": i18n.translate("Trop ressemblant au login").fetch(),
                    "too-short": i18n.translate("Trop court").fetch(),
                    "very-weak": i18n.translate("Trop faible").fetch(),
                    "weak": i18n.translate("Faible").fetch(),
                    "good": i18n.translate("OK").fetch(),
                    "strong": i18n.translate("OK").fetch()
            }
            jQuery('#passmeter').css('display','inline');
            jQuery("#password").valid();
        });


    }
})