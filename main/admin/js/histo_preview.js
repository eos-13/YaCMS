jQuery(document).ready(function(){
    jQuery('#selectable').find('li').each(function(){
    });
    jQuery('#selectable').selectable({
        selected: function(e,u){
            var id = jQuery(u.selected).find("a").attr('id');
            jQuery('.delete').removeClass("hidden");
            jQuery('.delete').attr('val',id);
            jQuery("iframe").attr('src',"histo_preview?action=view&id="+id);
        }
    });
    jQuery('button').button();
    jQuery('.delete').click(function(e){
        e.preventDefault();
        var id = jQuery(this).attr('val');
        jQuery('<div></div>')
            .attr('id','dialogDelete')
            .text(i18n.translate("La suppression est définitive. Confirmer?").fetch())
            .appendTo('body')
            .dialog({
                resizable: false,
                height:140,
                modal: true,
                title: i18n.translate("Confirmation").fetch(),
                buttons: {
                   Ok: function() {
                       del_preview(id);
                   },
                   Cancel: function() {
                       jQuery(this).dialog('close');
                       jQuery('#dialogDelete').remove();
                   }
                }
         });
    });
});
function del_preview(id)
{
    jQuery('a#'+id).parent().remove();
    jQuery('#dialogDelete').dialog('close');
    jQuery('#dialogDelete').remove();
    jQuery.ajax({
        url:"histo_preview",
        data:"action=delete&id="+id,
        type:"post",
        dataType: "json",
        success: function(a)
        {
            jQuery.growl({
                title: "Résultat",
                message: a.message,
                location: "tr",
                duration: 3200
            });
        }
    })
}

