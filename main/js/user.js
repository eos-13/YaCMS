jQuery(document).ready(function(){
    jQuery("button").button();
    jQuery("select").selectmenu({ width:400 });
});
jQuery(document).ready(function()
{
    if (jQuery('#edit_user').length)
    {

        jQuery('form#edit_user').validate(
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
                         url: "user?action=validate",
                         type: "post",
                         data:
                         {
                             id: function()
                             {
                                 return jQuery( "#id" ).val();
                             }
                         }
                     }
                 },
                 login:
                 {
                     required: true,
                     rangelength: [3, 30],
                     remote:
                     {
                         url: "user?action=validate",
                         type: "post",
                         data:
                         {
                             id: function()
                             {
                                 return jQuery( "#id" ).val();
                             }
                         }

                     }
                 },
                 password_again:
                 {
                     equalTo: "#password",

                 },
                 password:
                 {
                     password: "#login",

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

        });
        jQuery("#password").valid();
    }
})