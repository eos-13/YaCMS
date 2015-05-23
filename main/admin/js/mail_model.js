jQuery(document).ready(function(){
    jQuery('button').button();
});
jQuery(document).ready(function(){
    jQuery('#add').click(function(e){
        e.preventDefault();
        jQuery.ajax({
            url: 'mail_model',
            type:'post',
            dataType:'json',
            data:'action=add',
            success: function(a)
            {
                location.href="mail_model?id="+a.id;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#clone').click(function(e){
        e.preventDefault();
        var id = jQuery('#select_mail_model').val();
        jQuery.ajax({
            url: 'mail_model',
            type:'post',
            dataType:'json',
            data:'action=clone_mail_model&id='+id,
            success: function(a)
            {
                location.href="mail_model?id="+a.id;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#del').click(function(e)
    {
        e.preventDefault();
        var id = jQuery('#select_mail_model').val();

        var tmp_id   = jQuery("<input>").attr('type','hidden').attr('name','id').val(id);
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','mail_model');
        var tmp_action = jQuery("<input>").attr('type','hidden').attr('name','action').val("del");
        tmp_form.append(tmp_action)
                .append(tmp_id);

        jQuery("<div>")
            .attr('id','dialog')
            .text(i18n.translate('Confirmation:').fetch())
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
                title: i18n.translate("Confirmation de suppression").fetch(),
        });
    });
});


jQuery(document).ready(function(){
    jQuery('#select_mail_model').selectmenu({
        width: '400px',
        change: function(e,u){
            var id = jQuery(this).val();
            get_datas(id);
        }
    });
});

function get_datas(id)
{
    if (id>0)
    {
        jQuery.ajax({
            url: 'mail_model?id='+id,
            type:'post',
            dataType:'html',
            data:'action=get_content',
            success: function(a)
            {
                if (jQuery('#title').length>0)
                {
                    jQuery( "#title" ).rules( "remove" );

                }
                jQuery('#toggle').find('#toReplace').replaceWith("<div id='toReplace'>"+a+"</div>");
                jQuery('#toggle').css('display','block');
                v = jQuery( "#form_mail_model" ).validate({
                    onkeyup: false,
                    rules: {
                        "title": {
                            required: true,
                            remote: {
                                url: "mail_model?action=validate&id="+id,
                                type: "post",
                            }
                        }
                    }
                });
                v.form();
            }
        });
    } else {
        jQuery('#toReplace').replaceWith("<div id='toReplace'></div>")
    }
}

jQuery(document).ready(function()
{
    get_datas(jQuery('#select_mail_model').val());
});
jQuery(document).ready(function()
{
    jQuery('#save').click(function(e){
        e.preventDefault();
        var id = jQuery('#select_mail_model').val();
        jQuery('<input>')
        .attr('type','hidden')
        .attr('name','id')
        .val(id)
        .appendTo(jQuery('#form_mail_model'));

        jQuery('<input>')
        .attr('type','hidden')
        .attr('name','action')
        .val('update')
        .appendTo(jQuery('#form_mail_model'));
        jQuery('#form_mail_model').submit();
    });
});