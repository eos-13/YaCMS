jQuery(document).ready(function(){
    jQuery('button').button();
    jQuery('select#allpages').selectmenu({
        width: 400,
        change:function(){
            var id = jQuery(this).val();
            if (id != 0)
            {
                load_data(id);
            } else {
                jQuery('div#toReplace').replaceWith("<div id='toReplace'></div>");
            }
        }
    });
});
function load_data(id)
{
    jQuery.ajax({
        url: "pageondisk?action=get",
        data: "id="+id,
        type: "post",
        success: function(a)
        {
            jQuery('div#toReplace').replaceWith("<div id='toReplace'>"+a+"</div>");
            jQuery('#form_ondisk').find('select').selectmenu({width: 400});
            jQuery('#toReplace').find('button').button();
            jQuery('#reindex').click(function(e){
                e.preventDefault();
                var id = jQuery('#allpages').val();
                location.href='pageondisk?id='+id+"&action=reindex"
            });
            jQuery('#del').click(function(e){
                e.preventDefault();
                var id = jQuery('#allpages').val();
                location.href='pageondisk?id='+id+"&action=del"
            });
        }
    });
}
jQuery(document).ready(function()
{
    var id = jQuery('#allpages').val();
    if (id != "0")
    {
        load_data(id);
    }
});