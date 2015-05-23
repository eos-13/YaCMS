jQuery(document).ready(function()
{
    jQuery('#export_xml').click(function(e)
    {
        location.href="edit_page?id="+id+"&action=export_xml";
    });
});
jQuery(document).ready(function()
{
    jQuery('#deactivate_page').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            type: "POST",
            data: "action=deactivate_page",
            success: function(a){
                location.href="edit_page?id="+id;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#activate_page').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=activate_page",
            success: function(a)
            {
                location.href="edit_page?id="+id;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#del_page').click(function(e){
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
                    Ok: function()
                    {
                        del_page();
                    },
                    Cancel: function()
                    {
                        jQuery(this).dialog('close');
                        jQuery('#dialogDelete').remove();
                    }
                 }
             });
    });
});
function del_page()
{
    jQuery.ajax(
    {
        url: "edit_page?id="+id,
        type: "POST",
        data: "action=del_page",
        success: function(a)
        {
            jQuery('#dialogDelete').dialog('close');
            jQuery('#dialogDelete').remove();
            location.href="map";
        }
    });
}
jQuery(document).ready(function()
{
    jQuery('#add_page').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            data: "action=add_page",
            dataType: "json",
            type: "POST",
            success: function(a)
            {
                location.href="edit_page?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#clone_page').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=clone_page",
            success: function(a)
            {
                location.href="edit_page?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#make_a_draft').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=make_a_draft",
            success: function(a)
            {
                location.href="edit_page?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#publish_a_draft').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=publish_a_draft",
            success: function(a)
            {
                location.href="edit_page?id="+a.result;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#publish').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=publish",
            success: function(a)
            {
                location.href="edit_page?id="+a.result;
            }
        });
    });
});

jQuery(document).ready(function()
{
    jQuery('#add_section').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=add_section",
            success: function(a)
            {
                location.href="edit_page?load=1&id="+id;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('.del_section').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            var section_id = jQuery(this).val();
            jQuery('<div></div>').attr('id','dialogDelete')
                .text(i18n.translate("La suppression est définitive. Confirmer?").fetch())
                .appendTo('body')
                .dialog(
                {
                    resizable: false,
                    height:140,
                    modal: true,
                    title: i18n.translate("Confirmation").fetch(),
                    buttons:
                    {
                       Ok: function()
                       {
                           del_section(section_id);
                       },
                       Cancel: function()
                       {
                           jQuery(this).dialog('close');
                           jQuery('#dialogDelete').remove();
                       }
                    }
                });
        });
    });
});
function del_section(section_id)
{
    jQuery.ajax(
    {
        url: "edit_page?id="+id,
        type: "POST",
        data: "action=del_section&section_id="+section_id,
        success: function(a)
        {
            location.href="edit_page?load=1&id="+id;
        }
    });
}
jQuery(document).ready(function()
{
    jQuery('.deactivate_section').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            jQuery.ajax(
            {
                url: "edit_page?id="+id,
                dataType: "json",
                type: "POST",
                data: "action=deactivate_section&section_id="+jQuery(this).val(),
                success: function(a)
                {
                    location.href="edit_page?load=1&id="+id;
                }
            });
        });
    });
});
jQuery(document).ready(function(){
    jQuery('.activate_section').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            jQuery.ajax(
            {
                url: "edit_page?id="+id,
                dataType: "json",
                type: "POST",
                data: "action=activate_section&section_id="+jQuery(this).val(),
                success: function(a)
                {
                    location.href="edit_page?load=1&id="+id;
                }
            });
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('.clone_section').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            jQuery.ajax(
            {
                url: "edit_page?id="+id,
                dataType: "json",
                type: "POST",
                data: "action=clone_section&section_id="+jQuery(this).val(),
                success: function(a)
                {
                    location.href="edit_page?load=1&id="+id;
                }
            });
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('.clone_to_section').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            var page_id = jQuery(this).parent('div').find('select#all_page').val();
            jQuery.ajax(
            {
                url: "edit_page?id="+id+"&new_page_id="+page_id,
                dataType: "json",
                type: "POST",
                data: "action=clone_to_section&section_id="+jQuery(this).val(),
                success: function(a)
                {
                    location.href="edit_page?load=1&id="+a.result;
                }
            });
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('.move_section').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            var page_id = jQuery(this).parent('div').find('select#all_page').val();
            jQuery.ajax(
            {
                url: "edit_page?id="+id+"&new_page_id="+page_id,
                dataType: "json",
                type: "POST",
                data: "action=move_section&section_id="+jQuery(this).val(),
                success: function(a)
                {
                    location.href="edit_page?load=1&id="+a.result;
                }
            });
        });
    });
})

jQuery(document).ready(function()
{
    jQuery('#tabs').tabs();
    jQuery('button').button();
    jQuery('a.a_as_button').button();
    jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});
    jQuery("select:not([multiple])").selectmenu({width:'400px'});
});
jQuery(document).ready(function()
{
    jQuery('#save_sections').click(function()
    {
         var s = jQuery('#accordion').sortable( 'serialize' , { key:'s' });
         jQuery('#sort').val(s);
    });
})
jQuery(document).ready(function()
{
    var sortable_id = 0;
    jQuery('#accordion').accordion(
    {
        header: "> div > h3",
        heightStyle: "content",
        collapsible: true,
        active: false
    }).sortable({
        axis: "y",
        handle: "h3",
        start: function(e,u)
        {
            sortable_id = u.item.find("textarea").attr('id');
            jQuery("#"+sortable_id).tinymce().remove();
        },
        stop: function( event, ui )
        {
            ui.item.children( "h3" ).triggerHandler( "focusout" );
            jQuery( this ).accordion( "refresh" );
            tinyMCE.init(load_tiny_mce_var(sortable_id,content_css_set,base_root_path,readonly));
        }
    })
});
jQuery(document).ready(function()
{
    default_tinymce = load_tiny_mce_var(all_tinymce,content_css_set,base_root_path,readonly);
    tinyMCE.init(default_tinymce);
});

jQuery(document).ready(function()
{
    jQuery.validator.addMethod("urlpart", function(value, element)
    {
        return /^[\w\d_\-]*$/.test(value)
    }, jQuery.validator.format(i18n.translate("Only letters, numbers,  _ and -").fetch()));

    v = jQuery('#form_properties').validate(
    {
        onkeyup: false,
        rules: {
            "url":
            {
                required: true,
                urlpart: true,
                remote: {
                    url: "edit_page?action=validate&id="+id,
                    type: "post",
                }
            }
        }
    });
    v.form();
});
jQuery(document).ready(function()
{
    jQuery('#locked_for_edition').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=lock_page",
            success: function(a)
            {
                location.href="edit_page?id="+id;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#unlock_for_edition').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            dataType: "json",
            type: "POST",
            data: "action=unlock_page",
            success: function(a){
                location.href="edit_page?id="+id;
            }
        });
    });
});


var browser_current_file=false;
var browser_current_section_id=false;
jQuery(document).ready(function()
{
    jQuery('#dialog_file_browser').dialog(
    {
        modal: true,
        autoOpen: false,
        title: i18n.translate("Choisir un fichier").fetch(),
        width: 425,
        buttons: [
        {
            text: i18n.translate("Ok").fetch(),
            icons:
            {
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
    jQuery('.clickimage').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            browser_current_section_id = jQuery(this).attr('value');
            browse_image();
        });
    });
    jQuery('.browse').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            browser_current_section_id = jQuery(this).val();
            browse_image();
        });
    });
    jQuery('.browse_delete').each(function()
    {
        jQuery(this).click(function(e)
        {
            e.preventDefault();
            browser_current_section_id = jQuery(this).val();
            jQuery("#section_image_val_"+browser_current_section_id).val("");
            jQuery('#section_image_'+browser_current_section_id).css('background-image','url("'+base_path+'/img/No_Image.png")');
        });
    });

});
jQuery(document).ready(function()
{
    jQuery('#make_a_rev').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url: "edit_page?id="+id,
            type: "POST",
            data: "action=make_a_rev",
            success: function(a)
            {
                obj=jQuery.parseJSON(a);
                if (obj.result > 0)
                {
                    location.href="view_revision?id="+obj.result;
                }

            }
        });
    });
});

function browse_image()
{
    jQuery('#filetree').fileTree(
    {
        root : '/',
        script : base_path+'/lib/jQueryFileTree/connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
    }, function(file,dir)
    {
        //jQuery('#rep_thumb').val(dir);
        if(file) {
            browser_current_file = file;
        }
        if (file)
        {
            jQuery('#dialog_display').css('background-image','url("'+base_path+'/files/'+file+'")');
        } else {
            jQuery('#dialog_display').css('background-image','url("'+base_path+'/img/No_Image.png")');
        }

    });
    jQuery('#dialog_file_browser').dialog('open');
}
jQuery(document).ready(function()
{
    jQuery( "#verticaltabs" ).tabs({collapsible: true}).addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#verticaltabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
});

jQuery(document).ready(function()
{
    jQuery('#actionpanel').dialog(
    {
        autoOpen: false,
        modal: true,
        width:"690px",
        title: i18n.translate("Action").fetch(),
        buttons:
        [
            {
                text: i18n.translate("close").fetch(),
                click: function()
                {
                    jQuery( this ).dialog( "close" );
                }
            }
        ]
    });
});

jQuery(document).ready(function()
{
    jQuery('#actiondialog').click(function()
    {
        jQuery('#actionpanel').dialog('open');
    });
});