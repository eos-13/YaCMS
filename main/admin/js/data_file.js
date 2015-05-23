jQuery(document).ready(function(){
    jQuery('#fileTree').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         //Get datas
         if (file)
         {
             jQuery('#add_block').css('display','block');
             jQuery('#add_block').find('#path').val(file);
             refresh_edit_data(file,dir);
         } else {
             jQuery('#result-box').css('display','none');
             jQuery('#add_block').css('display','none');
             jQuery('#result').replaceWith("<div id='result' style='float:left;'></div>");
         }

     });
});
jQuery(document).ready(function()
{
    jQuery('#add_key').click(function(e)
    {
        e.preventDefault();
        var id = jQuery(this).parents("div#add_block").find('#path').val();
        var data_value = jQuery(this).parents("div#add_block").find('#val').val();
        var data_name = jQuery(this).parents("div#add_block").find('#key').val();
        jQuery.ajax({
            url: "data_file?action=add_key",
            data:"path="+id+"&val="+data_value+"&key="+data_name,
            type: "POST",
            success: function(a){
                obj = jQuery.parseJSON(a);
                jQuery.growl({
                    title: i18n.translate("Résultat").fetch(),
                    message: obj.result,
                    location: "tr",
                    duration: 3200
                });
                refresh_edit_data(id);
            },
        });
    });
});
jQuery(document).ready(function(){
    jQuery('button').button();
});
function modButton()
{
    jQuery('.modButton').click(function(e){
        e.preventDefault();
        var id = jQuery(this).parents("tbody").find('#id').val();
        var data_value = jQuery(this).parents("tbody").find('#data_value').val();
        var data_name = jQuery(this).parents("tbody").find('#data_name').val();
        jQuery.ajax({
            url: "data_file?action=edit",
            data:"id="+id+"&data_value="+data_value+"&data_name="+data_name,
            type: "POST",
            success: function(a){
                obj = jQuery.parseJSON(a);
                jQuery(document).ready(function(){
                    jQuery.growl({
                        title: i18n.translate("Résultat").fetch(),
                        message: obj.result,
                        location: "tr",
                        duration: 3200
                    });
                });
            }
        });
    });
}
function delButton()
{
    jQuery('.delButton').click(function(e){
        e.preventDefault();
        var id = jQuery(this).parents("tbody").find('#id').val();
        jQuery.ajax({
            url: "data_file?action=del",
            data:"id="+id,
            type: "POST",
            success: function(a){
                obj = jQuery.parseJSON(a);
                jQuery(document).ready(function(){
                    jQuery.growl({
                        title: i18n.translate("Résultat").fetch(),
                        message: obj.result,
                        location: "tr",
                        duration: 3200
                    });
                });
                refresh_edit_data(jQuery("div#add_block").find('#path').val());
            }
        });
    });
}
function refresh_edit_data(file,dir)
{
    jQuery.ajax({
        url: "data_file?action=get_info",
        data: "file="+file+"&dir="+dir,
        type: "POST",
        success: function(a)
        {
            jQuery('#result-box').css('display','block');
           obj = jQuery.parseJSON(a);
           html = "";
           if (obj.data.length > 0)
           {
               html += "<div style='width:100%'>";
               html += "<table width='100%' style='border-collapse: collapse'><tr><th width=150>"+i18n.translate('Clef').fetch()+"</th><th width=150>"+i18n.translate('Valeur').fetch()+"</th><th colspan=2>"+i18n.translate('Actions').fetch()+"</th></tr>";
               for (var i=0;i<obj.data.length;i++)
               {
                   html += "<tbody>";
                   html += "<tr><td><input id='id' name='id' type='hidden' value='"+obj.data[i].id+"'>";
                   html += "<input id='data_name' name='data_name' value='"+obj.data[i].data_name+"'></input>";
                   html += "<td>";
                   html += "<input id='data_value' name='data_value' value='"+obj.data[i].data_value+"'></input>";
                   html += "</td>"
                   html += "<td><button class='modButton'>"+i18n.translate("Modifier").fetch()+"</button></td>";
                   html += "<td><button class='delButton'>"+i18n.translate("Supprimer").fetch()+"</button></td></tr>"
                   html += "</tbody>";
               }
               html += "</table>";
               html += "</div>"

               jQuery('#result').replaceWith("<div id='result'>"+html+"</div>");
               jQuery('#result').find('button').button();
               modButton();
               delButton();
           } else {
               jQuery('#result').replaceWith("<div id='result'></div>");
           }
        }
    });
}