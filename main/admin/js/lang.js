jQuery(document).ready(function()
{
    jQuery('button').button();
    jQuery.widget(
            "custom.iconselectmenu",
            jQuery.ui.selectmenu,
            {
                _renderItem: function( ul, item )
                {
                    var li = jQuery( "<li>", { text: item.label } );
                    if ( item.disabled ) {
                        li.addClass( "ui-state-disabled" );
                    }
                    jQuery( "<span>",
                    {
                        style: item.element.attr( "data-style" ),
                        "class": "ui-icon " + item.element.attr( "data-class" )
                    })
                    .appendTo( li );
                    return li.appendTo( ul );
                }
    });
    jQuery("#select_lang")
        .iconselectmenu({width: 100})
        .iconselectmenu("menuWidget")
        .addClass( "ui-menu-icons flags" );
    jQuery("#select_editlang")
        .iconselectmenu({width: 100})
        .iconselectmenu("menuWidget")
        .addClass( "ui-menu-icons flags" );
    jQuery('#lang').click(function(e){
        var lang=jQuery('#select_lang').val();
        location.href="lang?action=lang&lang="+lang
    });
    jQuery('#editlang').click(function(e){
        var editlang=jQuery('#select_editlang').val();
        location.href="lang?action=editlang&editlang="+editlang
    });

});