jQuery(document).ready(function()
{
    jQuery('#slider').find('a').each(function()
    {
        var href = jQuery(this).prop('href');
        var href_thumb = jQuery(this).find('img').attr('src');
        var href_thumb_title = jQuery(this).find('img').attr('title');
        jQuery(this).prop('href','#');
        jQuery(this).click(function(e)
        {
            jQuery('.cloudzoom').data('CloudZoom').destroy();
            jQuery('.cloudzoom-blank').remove();
            jQuery('#cloudzoom').attr('title',href_thumb_title);
            launch_cloudzoom();
            cz = jQuery('#cloudzoom').data('CloudZoom');
            cz.loadImage(href_thumb,href );
            cz.refreshImage() ;
        });
    })
});
