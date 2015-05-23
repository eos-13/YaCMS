jQuery(document).ready(function(){
    jQuery('#selectable').find('li').each(function(){
    });
    jQuery( document ).tooltip({
        track: true,
        content: function () {
            return jQuery(this).prop('title');
        }
    });
    jQuery('#selectable').selectable({
        selected: function(e,u){
            var id = jQuery(u.selected).find("a").attr('id');
            var title = jQuery(u.selected).find("a").attr('title');
            var name = jQuery(u.selected).find("a").attr('name');
            jQuery('.delete').removeClass("hidden");
            jQuery('.delete').attr('val',id);
            jQuery('#info').replaceWith('<div id="info"><a target="_blank" class="as_a_button" href="view_revision?id='+id+'">'+i18n.translate("Revision du").fetch()+': '+name+" "+title+' </a></div>');
            jQuery('#info').find('.as_a_button').button();
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
                       del_revision(id);
                   },
                   Cancel: function() {
                       jQuery(this).dialog('close');
                       jQuery('#dialogDelete').remove();
                   }
                }
         });
    });
});
function del_revision(id)
{
    jQuery('a#'+id).parent().remove();
    jQuery('#dialogDelete').dialog('close');
    jQuery('#dialogDelete').remove();
    jQuery.ajax({
        url:"revision",
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
    });
}