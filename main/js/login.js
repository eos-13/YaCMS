jQuery(document).ready(function(){
    jQuery('#go').click(function(e){
        e.preventDefault();
        md5 = jQuery.md5(jQuery('#pass').val());
        jQuery('#pass').val(md5);
        jQuery('#login').submit();
    });
});