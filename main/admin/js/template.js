jQuery(document).ready(function(){
    jQuery('button').button();
});
jQuery(document).ready(function(){
    jQuery('#add').click(function(e){
        e.preventDefault();
        jQuery.ajax({
            url: 'template',
            type:'post',
            dataType:'json',
            data:'action=add',
            success: function(a)
            {
                location.href="template?id="+a.id;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#clone').click(function(e){
        e.preventDefault();
        var id = jQuery('#select_template').val();
        var type=jQuery('#select_template').find(':selected').parent().attr('id');

        jQuery.ajax({
            url: 'template',
            type:'post',
            dataType:'json',
            data:'action=clone_template&id='+id+"&type="+type,
            success: function(a)
            {
                location.href="template?id="+a.id+"&message="+a.message;
            }
        });
    });
});
jQuery(document).ready(function(){
    jQuery('#del').click(function(e)
    {
        e.preventDefault();
        var type=jQuery('#select_template').find(':selected').parent().attr('id');
        var id = jQuery('#select_template').val();

        var tmp_type = jQuery("<input>").attr('type','hidden').attr('name','type').val(type);
        var tmp_id   = jQuery("<input>").attr('type','hidden').attr('name','id').val(id);
        var tmp_form = jQuery("<form>").attr('id','dialog_form').attr('method','post').attr('action','template');
        var tmp_action = jQuery("<input>").attr('type','hidden').attr('name','action').val("del");
        tmp_form.append(tmp_type)
                .append(tmp_action)
                .append(tmp_id);

        jQuery("<div>")
            .attr('id','dialog')
            .text('Confirmation:')
            .append(tmp_form)
            .appendTo('body')
            .dialog({
                buttons:
                [
                    {
                        text: "OK",
                        click: function(e)
                        {
                            e.preventDefault();
                            jQuery('#dialog_form').submit();
                        },
                    },
                    {
                        text: "Cancel",
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
    jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});
    jQuery('select:not([multiple])').selectmenu({
        width: '400px',
        change: function(e,u){
            var type=jQuery(this).find(':selected').parent().attr('id');
            var id = jQuery(this).val();
            get_datas(type,id);

        }
    });
    if (loadedid)
    {
        var type=jQuery('#select_template').find(':selected').parent().attr('id');
        var id = jQuery('#select_template').val();
        get_datas(type,id);
    }
    jQuery('.fancy').fancybox({
        hideOnContentClick: true,
        closeClick  : false,
        autoSize: true,
        autoResize: true,
        fitToView: true,
        height: "600",
        helpers   : {
            overlay : {closeClick: true} // prevents closing when clicking OUTSIDE fancybox
        },
        afterClose: function(u)
        {
            console.log(jQuery(u.content).css('display','block'));
        }
    }).trigger("click");
});

function get_datas(type,id)
{
    loadedid = id;
    if(type == 'disk_main')
    {
        jQuery.ajax({
            url: 'template?id='+id,
            type:'post',
            dataType:'html',
            data:'action=edit_file_hd&file='+id+'&type='+type,
            success: function(a)
            {
                jQuery('#toggle').find('#toReplace').replaceWith("<div id='toReplace'>"+a+"</div>");
                jQuery('#toggle').css('display','block');
                jQuery('#del').css('display','none');
            }
        });
    } else if (type=='disk_custom')
    {
        jQuery.ajax({
            url: 'template?id='+id,
            type:'post',
            dataType:'html',
            data:'action=edit_file_hd&file='+id+'&type='+type,
            success: function(a)
            {
                jQuery('#toggle').find('#toReplace').replaceWith("<div id='toReplace'>"+a+"</div>");
                jQuery('#toggle').css('display','block');
                jQuery('#del').css('display','inline');
            }
        });
    } else {
        jQuery.ajax({
            url: 'template?id='+id,
            type:'post',
            dataType:'html',
            data:'action=edit_bdd',
            success: function(a)
            {
                jQuery('#toggle').find('#toReplace').replaceWith("<div id='toReplace'>"+a+"</div>");
                jQuery('#toggle').css('display','block');
                jQuery('#del').css('display','inline');
                jQuery( "#name" ).rules( "remove" );
                jQuery( "#name" ).rules( "add", {
                    required: true,
                    remote: {
                        url: "template?action=validate&id="+jQuery('#select_template').val(),
                        type: "post",
                        data: {
                            'name': function() {
                                return jQuery( "#name" ).val();
                            }
                        }
                    }
                });
                jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});

            }
        });
    }
}
jQuery(document).ready(function(){
    v = jQuery( "form" ).validate({
        onkeyup: false,
        rules: {
            "name": {
                required: true,
                remote: {
                    url: "template?action=validate&id="+loadedid,
                    type: "post",
                    data: {
                        'name': function() {
                            return jQuery( "#name" ).val();
                        }
                    }
                }
            }
        }
    });
    v.form();
});