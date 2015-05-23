jQuery(document).ready(function(){
    jQuery('#saveSorry').click(function(){
        var data = (jQuery('#offline').is(':checked')?"action=set_offline":"action=unset_offline");

        jQuery.ajax({
            url: "manage",
            data: data,
            dataType: "POST",
            success: function(a){
                location.href = "manage";
            },
        });
    });
    jQuery('button').button();
    jQuery('#tabs').tabs();
    jQuery('select').selectmenu();
});
jQuery(document).ready(function(){
    jQuery('#dot_image').click(function(){
        location.href='manage?action=gen_dot';
    });
});
jQuery(document).ready(function(){
    jQuery('#gen_backup').click(function(){
        location.href='manage?action=backup&type='+jQuery('#backup_type').val();
    });
});