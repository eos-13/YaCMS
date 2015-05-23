jQuery(document).ready(function()
{
    jQuery('#tabs').tabs();
    jQuery('button').button();
    jQuery('a.a_as_button').button();
    jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});
    jQuery("select:not([multiple])").selectmenu({width:'400px'});
});
jQuery(document).ready(function(){
    var sortable_id = 0;
    jQuery('#accordion').accordion(
    {
        header: "> div > h3",
        heightStyle: "content",
        collapsible: true,
        active: false
    });
    jQuery('#Remplacer').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
        jQuery('<div></div>')
            .attr('id','dialogReplace')
            .text(i18n.translate("Le remplacement ecrasera la page en cours. Confirmer?").fetch())
            .appendTo('body')
            .dialog({
                resizable: false,
                height:140,
                modal: true,
                title: i18n.translate("Confirmation").fetch(),
                buttons: {
                   Ok: function() {
                       remplacer(id);
                   },
                   Cancel: function() {
                       jQuery(this).dialog('close');
                       jQuery('#dialogReplace').remove();
                   }
                }
         });
    });
});
jQuery(document).ready(function()
{
    default_tinymce = load_tiny_mce_var(all_tinymce,content_css_set,base_root_path,readonly);
    tinyMCE.init(default_tinymce);
});
function remplacer(id)
{
    jQuery('a#'+id).parent().remove();
    jQuery('#dialogReplace').dialog('close');
    jQuery('#dialogReplace').remove();
    jQuery.ajax({
        url:"view_revision",
        data:"action=replace&id="+id,
        type:"post",
        dataType: "json",
        success: function(a)
        {
            jQuery.growl({
                title: i18n.translate("Résultat").fetch(),
                message: a.message,
                location: "tr",
                duration: 3200
            });
        }
    });
}