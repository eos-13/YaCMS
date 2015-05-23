function load_tiny_mce_user(content_css_set,base_path,max_char_user)
{
    tinyMCE.baseURL = base_path+"/main/js/tinymce/";// trailing slash important
    var tinyMCEPreInit = {
            suffix: '',
            base: base_path+"/main/js/tinymce/",
            query: ''
    };
    default_tinymce=
    {
        root: base_path,
        theme_url: (base_path+'/main/js/tinymce/themes/modern/theme.js').replace("\/\/", "\/"),
        mode : "exact",
        elements: "description",
        theme : "modern",
        menubar : false,
        relative_urls : false,
        plugins :  "  emoticons," +
                   "  hr," +
                   "  visualchars," +
                   "  nonbreaking ",
        content_css:content_css_set,
        max_chars: max_char_user, // this is a default value which can get modified later
        max_chars_indicator : ".maxCharsSpan",
        setup: function(ed)
        {
            ed.on('init', function(e)
            {
                jQuery('<div>')
                .addClass("maxCharsSpan")
                .addClass("mce-flow-layout-item")
                .css('margin-right','10px')
                .css('margin-top','3px')
                .css('float','right')
                .addClass("mce-path ").appendTo(
                    jQuery('#'+ed.id)
                    .parent()
                    .find('.mce-statusbar')
                    .find('.mce-container-body')
                );
            });
            wordcount = 0;
            ed.on('keyup', function(e)
            {
                text = tinyMCE.activeEditor.getContent().replace(/<[^>]*>/g, '').replace(/\s+/g, ' ');
                text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
                this.wordcount = tinyMCE.activeEditor.getParam('max_chars') - text.length;
                jQuery(tinyMCE.activeEditor.getParam('max_chars_indicator')).text( (this.wordcount<0?0:this.wordcount) +"/"+ tinyMCE.activeEditor.getParam('max_chars') );

            })
            ed.on('keydown',function(e)
            {
                if(this.wordcount <= 0 && e.keyCode != 8 && e.keyCode != 46) {
                     tinymce.dom.Event.cancel(this);
                     this.preventDefault();
                     this.stopPropagation();
                     return false;
                }
            });
        }
    }
    tinyMCE.init(default_tinymce);
}
