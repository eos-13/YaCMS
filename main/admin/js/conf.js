var v;
jQuery(document).ready(function(){
    jQuery('.delButton').each(function(){
        jQuery(this).click(function(e){
            e.preventDefault;
            var key=jQuery(this).val();
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
                       del_conf(key);
                   },
                   Cancel: function() {
                       jQuery(this).dialog('close');
                       jQuery('#dialogDelete').remove();
                   }
                }
            });
        });
    });
    jQuery.validator.addMethod("key_format", function(value, element, params) {
        return /^[\w\d_\-]*$/.test(value) ;
    }, jQuery.validator.format(i18n.translate("Only letters, numbers,  _ and -").fetch()));

    v = jQuery( "form" ).validate({
        onkeyup: false,
        rules: {
            "new_key": {
                required: true,
                key_format: true,
                remote: {
                    url: "conf?action=validate",
                    type: "post",
                    data: {
                        'new_key': function() {
                            return jQuery( "#new_key" ).val();
                        }
                    }
                }
            },
            "new_type": {
                required: true,
            }
        }
    });
    v.form();
});
jQuery(document).ready(function(){
    jQuery('#tabs').tabs();
    jQuery( document ).tooltip({
        track: true,
        content: function () {
            return jQuery(this).prop('title');
        }
    });
});

function del_conf(key)
{
    jQuery.ajax({
        url: "conf",
        type:"post",
        data: "action=delete&key="+key,
        success:function(a){
            location.href="conf";
        }
    });
}

jQuery(document).ready(function(){
    jQuery('select').selectmenu({my : "center-30 center-30", at: "center-30 center-30", of:"center-30 center-30", collision: "flip" , width:'auto'});
    jQuery('button').button();
    tinyMCE.init(load_tiny_mce_var("new_description",content_css_set,main_base_path));
});

    var delegatebutton={
        shouldOpenEditInPlace: function(aDOMNode, aSettingsDict, triggeringEvent)
        {
            remHTML=aDOMNode.html();
        },
        didOpenEditInPlace: function(aDOMNode, aSettingsDict)
        {
            jQuery(aDOMNode).find('button').button();
            var id=aDOMNode.attr('id');
            jQuery(aDOMNode).find('textarea.inplace_field')
            .attr('id',"tmce_"+id)
            .val(remHTML);
            remHTML = "";
        },
        didCloseEditInPlace: function(aDOMNode, aSettingsDict)
        {
        }
    };
    var remHTML = "";
    var delegateeip = {
        shouldOpenEditInPlace: function(aDOMNode, aSettingsDict, triggeringEvent)
        {
            remHTML=aDOMNode.html();
        },
        willOpenEditInPlace: function(aDOMNode, aSettingsDict)
        {
        },
        didOpenEditInPlace: function(aDOMNode, aSettingsDict)
        {
            jQuery(aDOMNode).find('button').button();
            var id=aDOMNode.attr('id');
            jQuery(aDOMNode).find('textarea.inplace_field')
            .attr('id',"tmce_"+id)
            .val(remHTML);
            tinyMCE.init(load_tiny_mce_var("tmce_"+id,content_css_set,main_base_path));
            remHTML = "";
        },
        shouldCloseEditInPlace: function(aDOMNode, aSettingsDict, triggeringEvent)
        {
               jQuery(aDOMNode).find('textarea.inplace_field').tinymce().remove();
        },
        willCloseEditInPlace: function(aDOMNode, aSettingsDict) {},
        didCloseEditInPlace: function(aDOMNode, aSettingsDict) {},
        missingCommaErrorPreventer:''
    };
