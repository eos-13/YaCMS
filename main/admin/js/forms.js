jQuery(document).ready(function()
{
    jQuery('#save').click(function(e)
    {
        e.preventDefault();
        var form = jQuery('#render').val();
        var title = jQuery(form).find('legend').html();
        var jsonData = jQuery('#toJson').text();
        form = "<div id='remove'>"+form+"</div>";
        var t = jQuery(form)
            .find('form')
            .attr('action',"form_result.php?id="+id)
            .attr('method','POST')
            .parents('div')
            .html();

        if (jQuery(t).find('.add-on input').length > 0)
        {
            t="<div id='remove'>"+t+"</div>";
            t = jQuery(t).find('.add-on input').each(function()
            {
                jQuery(this)
                    .parents('.controls')
                    .find('.add-on input')
                    .attr('name',function(iter)
                    {
                        var new_name= jQuery(this).parents("div").first().find('input').attr('name')+"_add-on";
                        return new_name;
                    })
                    .attr('id',function(iter)
                    {
                        var new_name= jQuery(this).parents("div").first().find('input').attr('name')+"_add-on";
                        return new_name;
                    })
            })
            .parents('form')
            .parents('div')
            .html();
        }
        if (jQuery(t).find('.btn-group button').length > 0)
        {
            t="<div id='remove'>"+t+"</div>";
            t = jQuery(t).find('.btn-group button').each(function()
            {
                var name = jQuery(this)
                    .parents('.controls')
                    .find('button.dropdown-toggle')
                    .parents("div.input-append").first().find('input').attr('name')+"_dropdown";
                jQuery('<input></input>')
                    .attr('type',"hidden")
                    .attr('name',name)
                    .attr('id',name)
                    .appendTo(jQuery(this).parent())
            })
            .parents('form')
            .parents('div')
            .html();
        }
        jQuery.ajax(
        {
            url:"forms?action=save",
            data:"content="+transform_post(t)+"&title="+transform_post(title)+"&jsonData="+transform_post(jsonData)+"&id="+id,
            type:"POST",
            dataType: "json",
            success: function(obj)
            {
                displayMsg(obj.message);
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#add').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url:"forms?action=add",
            type:"POST",
            dataType: "json",
            success: function(a)
            {
                displayMsg(a.message);
                location.href="forms?id="+a.id;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#clone').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax({
            url:"forms?action=clone",
            type:"POST",
            data: "id="+id,
            dataType: "json",
            success: function(a)
            {
                displayMsg(a.message);
                if (a.id)
                    location.href="forms?id="+a.id;
            }
        });
    });
});
jQuery(document).ready(function()
{
    jQuery('#del').click(function(e)
    {
        e.preventDefault();

        jQuery.ajax(
        {
            url:"forms?action=del",
            type:"POST",
            data: "id="+id,
            dataType: "json",
            success: function(a)
            {
                displayMsg(a.message);
                if (a.id)
                    location.href="forms";
            }
        });
    });
});

jQuery(document).ready(function()
{
    jQuery('#unpubli').click(function(e)
    {
        e.preventDefault();
        jQuery.ajax(
        {
            url:"forms?action=unpubli",
            type:"POST",
            data: "id="+id,
            dataType: "json",
            success: function(a)
            {
                displayMsg(a.message);
                if (a.id>0)
                {
                    jQuery('#publi').css('display','inline');
                    jQuery('#unpubli').css('display','none');
                    jQuery('#actually_publish').text(i18n.translate("Actuellement publié sur la page").fetch());
                }
            }
        });
    });
});

jQuery(document).ready(function()
{
    jQuery('select#list_initial').selectmenu(
    {
        width: 400,
        change: function()
        {
            if (jQuery(this).val() > 0)
            {
                location.href="forms?id="+jQuery(this).val();
            }
        }
    });
    jQuery('select#page').selectmenu(
    {
        width: 277,
    });
});
jQuery(document).ready(function()
{
    jQuery('#dialogpubli').dialog(
    {
        autoOpen:false,
        title: i18n.translate("Publié sur la page").fetch(),
        buttons: [
                 {
                     text: i18n.translate("OK").fetch(),
                     click: function() {
                         if (jQuery("#page").val()> 0)
                         {
                             publi();
                         }
                     }
                 },
                 {
                     text: i18n.translate("Cancel").fetch(),
                     click: function() {
                         jQuery( this ).dialog( "close" );
                     }
                 }

                 ],
                 modal: true
    });
});
jQuery(document).ready(function(){
    jQuery('#publi').click(function(e)
    {
        e.preventDefault();
        jQuery('#dialogpubli').dialog('open');
    });
});
jQuery(document).ready(function(){
    jQuery('button').button()
});

function publi()
{
    var form = jQuery('#render').val();
    var title = jQuery(form).find('legend').html();
    var jsonData = jQuery('#toJson').text();
    var page_refid = jQuery('#page').val();
    form = "<div id='remove'>"+form+"</div>";
    var t = jQuery(form)
        .find('form')
        .attr('action',"form_result.php?id="+id)
        .attr('method','POST')
        .parents('div')
        .html();
    jQuery.ajax(
    {
        url:"forms?action=publish",
        data:"content="+transform_post(t)+"&title="+transform_post(title)+"&jsonData="+transform_post(jsonData)+"&id="+id+"&page_refid="+page_refid,
        type:"POST",
        dataType: "json",
        success: function(a)
        {
            displayMsg(a.message);
            jQuery('#publi').css('display',"none");
            jQuery('#unpubli').css('display',"inline");
            jQuery('#dialogpubli').dialog('close');
            jQuery('#actually_not_publish').text(i18n.translate("Publié!").fetch());
        }
    });
}

function displayMsg(msg)
{
    jQuery.growl({
        title: i18n.translate("Résultat").fetch(),
        message: msg,
        location: 'tr',
    });
}
function transform_post(a)
{
    //jQuery.base64.utf8encode = true;
    //a = jQuery.base64.atob(a,true);
    a = encodeURIComponent(a);
    return a;
}