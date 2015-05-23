var pId=false;
var pDir = false;
var pDir_type = false;
jQuery(document).ready(function()
{
    jQuery('#tabs').tabs();
    if (loadedTab)
    {
        jQuery("#tabs").tabs( "option", "active", loadedTab );
    }
    jQuery('button').button();
});


function load_content_css(dir,id)
{
    if (null != id)
    {
        jQuery.ajax({
            url:"edit?action=get_content",
            data: "type=css&dir="+pDir+"&id="+pId,
            type: "POST",
            success:function(a)
            {
                jQuery('#textarea_css').val(a);
                jQuery('#display_textarea_css').css('display','block');
                if (dir != "main") {
                    jQuery('#del_css').css('display','inline');
                } else {
                    jQuery('#del_css').css('display','none');
                }
            }
        });
    } else {
        jQuery('#textarea_css').val(''),
        jQuery('#textarea_css').css('display',"none");
    }
}

function load_content_js(dir,id)
{
    if (null != id)
    {
        jQuery.ajax(
        {
            url:"edit?action=get_content",
            data: "type=js&dir="+pDir+"&id="+pId,
            type: "POST",
            success:function(a){
                jQuery('#textarea_js').val(a);
                jQuery('#display_textarea_js').css('display','block');
                if (dir != "main") {
                    jQuery('#del_js').css('display','inline');
                } else {
                    jQuery('#del_js').css('display','none');
                }
            }
        });
    } else {
        jQuery('#textarea_js').val(''),
        jQuery('#display_textarea_js').css('display',"none");
    }
}
jQuery(document).ready(function()
{
    jQuery('#save_js').click(function(e)
    {
        e.preventDefault();
        var type = "js";
        jQuery("<input>").attr('type','hidden').attr('name','type').val(type).appendTo('#form_js');
        jQuery("<input>").attr('type','hidden').attr('name','dir').val(pDir).appendTo('#form_js');
        jQuery("<input>").attr('type','hidden').attr('name','id').val(pId).appendTo('#form_js');
        jQuery('#form_js').submit();
    });
});
jQuery(document).ready(function()
{
    jQuery('#save_css').click(function(e)
    {
        e.preventDefault();
        var type = "css";
        jQuery("<input>").attr('type','hidden').attr('name','type').val(type).appendTo('#form_css');
        jQuery("<input>").attr('type','hidden').attr('name','dir').val(pDir).appendTo('#form_css');
        jQuery("<input>").attr('type','hidden').attr('name','id').val(pId).appendTo('#form_css');
        jQuery('#form_css').submit();
    });
});
jQuery(document).ready(function()
{
    jQuery.validator.addMethod("filename", function(value, element) {
        return /^[\w\d_\-\.]*$/.test(value)
    }, jQuery.validator.format(i18n.translate("Only letters, numbers, .,  _ and -").fetch()));
});

jQuery(document).ready(function()
{
    jQuery('#clone_js').click(function(e)
    {
        e.preventDefault();
        var dir=jQuery('#js_list').find(':selected').parent().attr('id');
        var id = jQuery('#js_list').val();
        var type = "js";
        var tmp_type = jQuery("<input>").attr('type','hidden').attr('name','type').val(type);
        var tmp_dir  = jQuery("<input>").attr('type','hidden').attr('name','dir').val(pDir);
        var tmp_id   = jQuery("<input>").attr('type','hidden').attr('name','id').val(pId);
        var tmp_name = jQuery("<input>").attr('type','text').attr('id','name').attr('name','name').val("Clone_"+pId)
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','edit?action=clone')

        tmp_form.append(tmp_type)
                .append(tmp_dir)
                .append(tmp_id)
                .append(tmp_name);

        jQuery("<div>")
        .attr('id','dialog')
        .text(i18n.translate('Nouveau nom').fetch())
        .append(tmp_form)
        .appendTo('body')
        .dialog({
            buttons:
            [
                {
                    text: i18n.translate("OK").fetch(),
                    click: function(e)
                    {
                        e.preventDefault();
                        jQuery('#dialog_form').submit();
                    },
                },
                {
                    text: i18n.translate("Cancel").fetch(),
                    click: function()
                    {
                        jQuery( this ).dialog( "close" );
                        jQuery( this).dialog( "destroy");
                        jQuery('#dialog').remove();
                    },
                },

            ],
            modal: true,
            title: i18n.translate("Clone").fetch()+" "+pId,
            open: function(e,i){
                v=jQuery('#dialog_form').validate(
                {
                    onkeyup: false,
                    rules: {
                        "name":
                        {
                            required: true,
                            filename: true,
                            remote: {
                                url: "edit?action=validate&type=js",
                                type: "post",
                                data:
                                {
                                    'filename': function()
                                    {
                                        return jQuery( "#name" ).val();
                                    }
                                }
                            }
                        }
                    }
                });
                v.form();
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#clone_css').click(function(e)
    {
        e.preventDefault();
        var type = "css";
        var tmp_type = jQuery("<input>").attr('type','hidden').attr('name','type').val(type);
        var tmp_dir  = jQuery("<input>").attr('type','hidden').attr('name','dir').val(pDir);
        var tmp_id   = jQuery("<input>").attr('type','hidden').attr('name','id').val(pId);
        var tmp_name = jQuery("<input>").attr('type','text').attr('id','name').attr('name','name').val("Clone_"+pId)
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','edit?action=clone')

        tmp_form.append(tmp_type)
                .append(tmp_dir)
                .append(tmp_id)
                .append(tmp_name);

        jQuery("<div>")
            .attr('id','dialog')
            .text('Nouveau nom')
            .append(tmp_form)
            .appendTo('body')
            .dialog(
            {
                buttons:
                [
                    {
                        text: i18n.translate("OK").fetch(),
                        click: function(e)
                        {
                            e.preventDefault();
                            jQuery('#dialog_form').submit();
                        },
                    },
                    {
                        text: i18n.translate("Cancel").fetch(),
                        click: function()
                        {
                            jQuery( this ).dialog( "close" );
                            jQuery( this).dialog( "destroy");
                            jQuery('#dialog').remove();
                        },
                    },
                ],
                modal: true,
                title: i18n.translate("Clone").fetch()+" "+pId,
                open: function(e,i)
                {
                    v=jQuery('#dialog_form').validate(
                    {
                        onkeyup: false,
                        rules: {
                            "name":
                            {
                                required: true,
                                filename: true,
                                remote:
                                {
                                    url: "edit?action=validate&type=css",
                                    type: "post",
                                    data:
                                    {
                                    'filename': function()
                                    {
                                        return jQuery( "#name" ).val();
                                    }
                                }
                            }
                        }
                    }
                });
                v.form();
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#del_js').click(function(e)
    {
        e.preventDefault();
        var type = "js";
        var tmp_type = jQuery("<input>").attr('type','hidden').attr('name','type').val(type);
        var tmp_dir  = jQuery("<input>").attr('type','hidden').attr('name','dir').val(pDir);
        var tmp_id   = jQuery("<input>").attr('type','hidden').attr('name','id').val(pId);
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','edit?action=delete')

        tmp_form.append(tmp_type)
                .append(tmp_dir)
                .append(tmp_id)

        jQuery("<div>")
            .attr('id','dialog')
            .text(i18n.translate('Confirmation de la suppression').fetch())
            .append(tmp_form)
            .appendTo('body').dialog(
            {
                buttons:
                [
                    {
                        text: i18n.translate("OK").fetch(),
                        click: function(e)
                        {
                            e.preventDefault();
                            jQuery('#dialog_form').submit();
                        },
                    },
                    {
                        text: i18n.translate("Cancel").fetch(),
                        click: function()
                        {
                            jQuery( this ).dialog( "close" );
                            jQuery( this).dialog( "destroy");
                            jQuery('#dialog').remove();
                        },
                    },
                ],
                modal: true,
                title: i18n.translate("Supprimer").fetch()+" "+pId,
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#del_css').click(function(e)
    {
        e.preventDefault();
        var type = "css";
        var tmp_type = jQuery("<input>").attr('type','hidden').attr('name','type').val(type);
        var tmp_dir  = jQuery("<input>").attr('type','hidden').attr('name','dir').val(pDir);
        var tmp_id   = jQuery("<input>").attr('type','hidden').attr('name','id').val(pId);
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','edit?action=delete')

        tmp_form.append(tmp_type)
                .append(tmp_dir)
                .append(tmp_id)

        jQuery("<div>")
            .attr('id','dialog')
            .text(i18n.translate('Confirmation de la suppression').fetch())
            .append(tmp_form)
            .appendTo('body').dialog(
            {
                buttons:
                [
                    {
                        text: i18n.translate("OK").fetch(),
                        click: function(e)
                        {
                            e.preventDefault();
                            jQuery('#dialog_form').submit();
                        },
                    },
                    {
                        text: i18n.translate("Cancel").fetch(),
                        click: function()
                        {
                            jQuery( this ).dialog( "close" );
                            jQuery( this).dialog( "destroy");
                            jQuery('#dialog').remove();
                        },
                    },
                ],
                modal: true,
                title: i18n.translate("Supprimer").fetch()+" "+pId,
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#new_css').click(function(e)
    {
        e.preventDefault();
        var dir="customer";
        var tmp_type  = jQuery("<input>").attr('type','hidden').attr('name','type').val("css");
        var tmp_dir  = jQuery("<input>").attr('type','hidden').attr('name','dir').val(dir);
        var tmp_name = jQuery("<input>").attr('type','text').attr('id','name').attr('name','name');
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','edit?action=new')

        tmp_form.append(tmp_type)
                .append(tmp_dir)
                .append(tmp_name);

        jQuery("<div>")
            .attr('id','dialog')
            .text(i18n.translate('Nouveau fichier CSS:').fetch())
            .append(tmp_form)
            .appendTo('body').dialog(
            {
                buttons:
                [
                    {
                        text: i18n.translate("OK").fetch(),
                        click: function(e)
                        {
                            e.preventDefault();
                            jQuery('#dialog_form').submit();
                        },
                    },
                    {
                        text: i18n.translate("Cancel").fetch(),
                        click: function()
                        {
                            jQuery( this ).dialog( "close" );
                            jQuery( this).dialog( "destroy");
                            jQuery('#dialog').remove();
                        },
                    },
                ],
                modal: true,
                title: i18n.translate("Nouveau CSS").fetch(),
                open: function(e,i)
                {
                    v=jQuery('#dialog_form').validate(
                    {
                        onkeyup: false,
                        rules: {
                            "name":
                            {
                                required: true,
                                filename: true,
                                remote:
                                {
                                    url: "edit?action=validate&type=css",
                                    type: "post",
                                    data:
                                    {
                                    'filename': function()
                                    {
                                        return jQuery( "#name" ).val();
                                    }
                                }
                            }
                        }
                    }
                });
                v.form();
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#new_js').click(function(e)
    {
        e.preventDefault();
        var dir="customer";
        var tmp_type  = jQuery("<input>").attr('type','hidden').attr('name','type').val("js");
        var tmp_dir  = jQuery("<input>").attr('type','hidden').attr('name','dir').val(dir);
        var tmp_name = jQuery("<input>").attr('type','text').attr('id','name').attr('name','name');
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','edit?action=new')

        tmp_form.append(tmp_type)
                .append(tmp_dir)
                .append(tmp_name);

        jQuery("<div>")
            .attr('id','dialog')
            .text(i18n.translate('Nouveau fichier js').fetch()+": ")
            .append(tmp_form)
            .appendTo('body').dialog(
            {
                buttons:
                [
                    {
                        text: i18n.translate("OK").fetch(),
                        click: function(e)
                        {
                            e.preventDefault();
                            jQuery('#dialog_form').submit();
                        },
                    },
                    {
                        text: i18n.translate("Cancel").fetch(),
                        click: function()
                        {
                            jQuery( this ).dialog( "close" );
                            jQuery( this).dialog( "destroy");
                            jQuery('#dialog').remove();
                        },
                    },
                ],
                modal: true,
                title: i18n.translate("Nouveau js").fetch()+": ",
                open: function(e,i)
                {
                    v=jQuery('#dialog_form').validate(
                    {
                        onkeyup: false,
                        rules: {
                            "name":
                            {
                                required: true,
                                filename: true,
                                remote:
                                {
                                    url: "edit?action=validate&type=js",
                                    type: "post",
                                    data:
                                    {
                                    'filename': function()
                                    {
                                        return jQuery( "#name" ).val();
                                    }
                                }
                            }
                        }
                    }
                });
                v.form();
            }
        });
    });
});
jQuery(document).ready(function(){
    var id_css = jQuery('#css_list').val();
    var id_js = jQuery('#js_list').val();
    if (id_js != "0")
    {
        var dir=jQuery('#js_list').find(':selected').parent().attr('id');
        load_content_js(dir,id_js);
    } else if (id_css != "0")
    {
        var dir=jQuery('#css_list').find(':selected').parent().attr('id');
        load_content_js(dir,id_css);
    }


});

jQuery(document).ready(function(){
    jQuery('#css_ft_main').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/main_css_connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         pId = file;
         pDir = "/main/css"+dir;
         load_content_css(dir,file);
     });
});
jQuery(document).ready(function(){
    jQuery('#css_ft_customer').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/customer_css_connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         pId = file;
         pDir = "/customer/css"+dir;
         load_content_css(dir,file);
     });
});
jQuery(document).ready(function(){
    jQuery('#js_ft_customer').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/customer_js_connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         pId = file;
         pDir = "/customer/js"+dir;

         load_content_js(dir,file);
     });
});
jQuery(document).ready(function(){
    jQuery('#js_ft_main').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/main_js_connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         pId = file;
         pDir = "/main/js"+dir;
         load_content_js(dir,file);
     });
});