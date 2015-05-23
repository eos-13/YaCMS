jQuery(document).ready(function(){
    jQuery('button').button();
    jQuery('#forms_list').selectmenu({
        width:400,
        change: function(){
            if (jQuery(this).val()>0)
                location.href="result_forms?id="+jQuery(this).val();
            else
                location.href="result_forms";
        }
    });
});

jQuery(document).ready(function(){
    jQuery('#deleteAll').click(function(e){
        e.preventDefault();
        jQuery('<div></div>').attr('id','dialogDelete')
                     .text(i18n.translate("La suppression est définitive. Confirmer?").fetch())
                     .appendTo('body')
                     .dialog({
                         resizable: false,
                         height:140,
                         modal: true,
                         title: i18n.translate("Confirmation").fetch(),
                         buttons: {
                            Ok: function() {
                                del_results();
                            },
                            Cancel: function() {
                                jQuery(this).dialog('close');
                                jQuery('#dialogDelete').remove();
                            }
                         }
                     });
    });
});
function del_results(){
    jQuery.ajax({
        url: "result_forms?id="+id,
        type: "POST",
        dataType: "json",
        data: "action=del",
        success: function(a)
        {
            jQuery('#dialogDelete').dialog('close');
            jQuery('#dialogDelete').remove();
            location.href="result_forms?message="+a;
        }
    });
}