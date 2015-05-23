jQuery(document).ready(function(){
    jQuery('#disallow').click(function(e){
        e.preventDefault();
        jQuery('#robot').val('User-agent: *\nDisallow: /');
    });
    jQuery('#allow').click(function(e){
        e.preventDefault();
        jQuery('#robot').val('User-agent: *\nDisallow:');
    })

});
