var browser_current_file=false;
var browser_current_section_id=false;
jQuery(document).ready(function()
{
    jQuery("#accordion").accordion();
    jQuery('button').button();
    jQuery('.menu_active').buttonset();

    jQuery("select[multiple]").multiselect(
    {
        header: i18n.translate("Lien à afficher").fetch(),
        noneSelectedText: i18n.translate("Lien à afficher").fetch()
    });
    jQuery("select:not([multiple])").selectmenu({width:'400px'});
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
            jQuery("#image_val_-"+browser_current_section_id).val("");
            jQuery('#image_'+browser_current_section_id).css('background-image','url("'+base_path+'/img/No_Image.png")');
        });
    });
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
                    jQuery('#image_'+browser_current_section_id).css('background-image', 'url("'+base_path+'/files/'+browser_current_file+'")')
                    jQuery("#image_val_"+browser_current_section_id).val(browser_current_file);
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
            click: function()
            {
                jQuery( this ).dialog( "close" );
            },
        }
        ]
    });
});
jQuery(document).ready(function()
{
    default_tinymce = load_tiny_mce_var(all_tinymce,content_css_set,base_root_path,false);
    tinyMCE.init(default_tinymce);
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