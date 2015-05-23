jQuery(document).ready(function(){
    jQuery('#activate').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
        jQuery.ajax({
            url: "edit_section?id="+id,
            type: "POST",
            data: "action=activate",
            success: function(a){
                location.href="edit_section?id="+id;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#del').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
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
                       del_section(id);
                   },
                   Cancel: function() {
                       jQuery(this).dialog('close');
                       jQuery('#dialogDelete').remove();
                   }
                }
            });
    });
});
function del_section(id)
{
    jQuery.ajax({
        url: "edit_section?id="+id,
        type: "POST",
        data: "action=delete",
        success: function(a){
            location.href="map";
        }
    });

}
jQuery(document).ready(function(){
    jQuery('#deactivate').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
        jQuery.ajax({
            url: "edit_section?id="+id,
            type: "POST",
            data: "action=desactivate",
            success: function(a){
                location.href="edit_section?id="+id;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#clone').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
        jQuery.ajax({
            url: "edit_section?id="+id,
            type: "POST",
            dataType: "json",
            data: "action=clone",
            success: function(a){
                location.href="edit_section?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#clone_to_page').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
        var new_page = jQuery('#new_page').val();
        jQuery.ajax({
            url: "edit_section?id="+id,
            type: "POST",
            dataType: "json",
            data: "action=clone_to_page&new_page="+new_page,
            success: function(a){
                location.href="edit_section?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#move_to_page').click(function(e){
        e.preventDefault();
        var id = jQuery(this).val();
        var new_page = jQuery('#new_page').val();
        jQuery.ajax({
            url: "edit_section?id="+id,
            type: "POST",
            dataType: "json",
            data: "action=move_to_page&new_page="+new_page,
            success: function(a){
                location.href="edit_section?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('button').button();
    jQuery('a.a_as_button').button();
    jQuery('select').selectmenu({width: 400});
});
jQuery(document).ready(function(){
    tinyMCE.init(default_tinymce);
});
jQuery(document).ready(function()
{
        jQuery('#locked_for_edition').click(function(e){
            var id = jQuery(this).val();
            e.preventDefault();
            jQuery.ajax({
                url: "edit_section?id="+id,
                dataType: "json",
                type: "POST",
                data: "action=lock_page",
                success: function(a){
                    location.href="edit_section?id="+id;
                }
            });
        });
});
jQuery(document).ready(function()
{
        jQuery("#unlock_for_edition").click(function(e){
            var id = jQuery(this).val();
            e.preventDefault();
            jQuery.ajax({
                url: "edit_section?id="+id,
                dataType: "json",
                type: "POST",
                data: "action=unlock_page",
                success: function(a){
                    location.href="edit_section?id="+id;
                }
            });
    });
});
var browser_current_file=false;
var browser_current_section_id=false;
jQuery(document).ready(function()
{
    jQuery('#dialog_file_browser').dialog({
        modal: true,
        autoOpen: false,
        title: i18n.translate("Choisir un fichier").fetch(),
        width: 425,
        buttons: [
        {
            text: i18n.translate("Ok").fetch(),
            icons: {
                primary: "ui-icon-check"
            },
            click: function()
            {
                if (browser_current_file)
                {
                    jQuery('#section_image_'+browser_current_section_id).css('background-image', 'url("'+base_path+'/files/'+browser_current_file+'")')
                    jQuery("#section_image_val_"+browser_current_section_id).val(browser_current_file);
                }
                jQuery( this ).dialog( "close" );
            },
        },
        {
            text: i18n.translate("Cancel").fetch(),
            icons:
            {
                primary: "ui-icon-closethick"
            },
            click: function() {
                jQuery( this ).dialog( "close" );
            },
        }
        ]
    });
    jQuery('.clickimage').each(function(){
        jQuery(this).click(function(e){
            e.preventDefault();
            browser_current_section_id = jQuery(this).attr('value');
            browse_image();
        });
    });
    jQuery('.browse').each(function(){
        jQuery(this).click(function(e){
            e.preventDefault();
            browser_current_section_id = jQuery(this).val();
            browse_image();
        });
    });
    jQuery('.browse_delete').each(function(){
        jQuery(this).click(function(e){
            e.preventDefault();
            browser_current_section_id = jQuery(this).val();
            jQuery("#section_image_val_"+browser_current_section_id).val("");
            jQuery('#section_image_'+browser_current_section_id).css('background-image','url("'+base_path+'/img/No_Image.png")');
        });
    });

});

function browse_image()
{
    jQuery('#filetree').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
    }, function(file,dir)
    {
        //jQuery('#rep_thumb').val(dir);
        if(file) { browser_current_file = file; }
        if (file)
        {
            jQuery('#dialog_display').css('background-image','url("'+base_path+'/files/'+file+'")');
        } else {
            jQuery('#dialog_display').css('background-image','url("'+base_path+'/img/No_Image.png")');
        }
    });
    jQuery('#dialog_file_browser').dialog('open');
}