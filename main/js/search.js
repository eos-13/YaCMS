jQuery(document).ready(function(){
    jQuery( "#search" ).autocomplete({
        source: function( request, response ) {
            jQuery.ajax({
                url: "search",
                dataType: "json",
                type: "post",
                data: 'action=autocomplete&search_term='+jQuery('#search').val(),
                success: function( data )
                {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui )
        {
            OriginalString = ui.item.label;
            var StrippedString = OriginalString.replace(/(<([^>]+)>)/ig,"");
            jQuery('#search').val(StrippedString);
            jQuery('#search_form').submit()
            return false;

        },
        open: function() {
            jQuery( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            jQuery( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return jQuery( "<li>" )
        .append( item.label )
        .appendTo( ul );
};
});