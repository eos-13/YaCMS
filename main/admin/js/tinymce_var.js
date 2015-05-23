var extra_tme_plugin = {};
function load_tiny_mce_var(all_tinymce,content_css_set,base_path,readonly)
{

    tinyMCE.baseURL = base_path+"/main/admin/js/tinymce/";// trailing slash important
    var tinyMCEPreInit = {
            suffix: '',
            base: base_path+"/main/admin/js/tinymce/",
            query: ''
    };
    customFormat = [
                     {title: 'Headers', items: [
                                                {title: 'Header 1', format: 'h1'},
                                                {title: 'Header 2', format: 'h2'},
                                                {title: 'Header 3', format: 'h3'},
                                                {title: 'Header 4', format: 'h4'},
                                                {title: 'Header 5', format: 'h5'},
                                                {title: 'Header 6', format: 'h6'}
                                                ]
                     },
                     {title: 'Inline', items: [
                                                {title: 'Bold', icon: 'bold', format: 'bold'},
                                                {title: 'Italic', icon: 'italic', format: 'italic'},
                                                {title: 'Underline', icon: 'underline', format: 'underline'},
                                                {title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
                                                {title: 'Superscript', icon: 'superscript', format: 'superscript'},
                                                {title: 'Subscript', icon: 'subscript', format: 'subscript'},
                                                {title: 'Code', icon: 'code', format: 'code'}
                                              ]
                     },
                     {title: 'Blocks', items: [
                                                   {title: 'Paragraph', format: 'p'},
                                                 {title: 'Blockquote', format: 'blockquote'},
                                                 {title: 'Div', format: 'div'},
                                                 {title: 'Pre', format: 'pre'}
                                              ]
                     },
                     {title: 'Alignment', items: [
                                                  {title: 'Left', icon: 'alignleft', format: 'alignleft'},
                                                  {title: 'Center', icon: 'aligncenter', format: 'aligncenter'},
                                                  {title: 'Right', icon: 'alignright', format: 'alignright'},
                                                  {title: 'Justify', icon: 'alignjustify', format: 'alignjustify'}
                                                 ]
                     },
                     {title: 'Image', items: [
                                                {
                                                    title: 'Image Left',
                                                    selector: 'img',
                                                    styles: {
                                                        'float': 'left',
                                                        'margin': '0 10px 0 10px'
                                                    }
                                                 },
                                                 {
                                                     title: 'Image Right',
                                                     selector: 'img',
                                                     styles: {
                                                         'float': 'right',
                                                         'margin': '0 0 10px 10px'
                                                     }
                                                 }
                                             ]
                     },
                    ];
    if (typeof custom_menu !== 'undefined')
    {
        customFormat.push(custom_menu);
    }
    p_list = list_plugin();
    toolbar2 = list_toolbar2();
    default_tinymce={
            root: base_path,
            mode : "exact",
            extended_valid_elements : "address[class|name|id],article[class|name|id],aside[class|name|id],audio[class|name|id],bdi[class|name|id],caption[class|name|id],canvas[class|name|id],datalist[class|name|id],details[class|name|id],dialog[class|name|id],embed[class|name|id],figure[class|name|id],figcaption[class|name|id],footer[class|name|id],header[class|name|id],keygen[class|name|id],mark[class|name|id],menuitem[class|name|id],meter[class|name|id],nav[class|name|id],output[class|name|id],progress[class|name|id],rp[class|name|id],rt[class|name|id],ruby[class|name|id],section[class|name|id],source[class|name|id],summary[class|name|id],time[class|name|id],track[class|name|id],video[class|name|id],wbr[class|name|id]",
            readonly: readonly,
            elements: all_tinymce,
            theme : "modern",
            theme_url: (base_path+'/main/js/tinymce/themes/modern/theme.js').replace("\/\/", "\/"),
            relative_urls : false,
            plugins : p_list,
            content_css:content_css_set,
            file_browser_callback : elFinderBrowser,
            style_formats: customFormat,
            convert_urls: false,
            valid_elements: "*[*]",
            verify_html: false,
            image_advtab: true,
            toolbar1: "undo redo | styleselect | bold italic underline separator strikethrough |  | bullist numlist outdent indent | anchor link unlink image ",
            toolbar2: toolbar2,
            color_picker_callback: colPicker,
            setup: function(editor)
            {
                editor.on('submit', function(e)
                {
                    var inst_id = tinymce.activeEditor.id.replace(/_[0-9]*$/,'');
                    if (typeof usewrapper !== 'undefined' && (inst_id == "section" || inst_id == "tinymce"))
                    {
                        usewrapper = false;
                        html = "<div>"+editor.getContent()+"</div>";
                        var count = jQuery(html).find('div.main-wrp').length;
                        if (count > 0)
                        {
                            html = jQuery(html).html();
                            jQuery(editor.getElement()).val(html);
                            editor.setContent(html);
                        }
                    }
                    if (typeof usefooterwrapper !== 'undefined' && inst_id == 'footer')
                    {
                        usefooterwrapper = false;
                        html = "<div>"+editor.getContent()+"</div>";
                        var count = jQuery(html).find('footer.footer').length;
                        if (count > 0)
                        {
                            html = jQuery(html).html();
                            jQuery(editor.getElement()).val(html);
                            editor.setContent(html);
                        }
                    }
                    if (typeof useheaderwrapper !== 'undefined' && inst_id == 'header')
                    {
                        useheaderwrapper = false;
                        html = "<div>"+editor.getContent()+"</div>";
                        var count = jQuery(html).find('header.header').length;
                        if (count > 0)
                        {
                            html = jQuery(html).html();
                            jQuery(editor.getElement()).val(html);
                            editor.setContent(html);
                        }
                    }
                    if (typeof usecustomwrapper !== 'undefined')
                    {
                        html = editor.getContent();
                        var element = usecustomwrapper['element'];
                        var class_css = usecustomwrapper['class_css'];
                        usecustomwrapper = false;
                        html = editor.getContent();
                        html = jQuery(html).html();
                        jQuery(editor.getElement()).val(html);
                        editor.setContent(html);
                    }
                });

                editor.on("KeyUp",function (editor)
                {
                    make_wysiwyg(tinymce.activeEditor); // if wrapper has been deleted, add it back
                });
            }
    }
    default_tinymce['init_instance_callback']=make_wysiwyg;
    if (typeof extra_tme_plugin !== "undefined" && typeof extra_tme_plugin === "object")
    {
        default_tinymce['external_plugins'] = extra_tme_plugin;
    }
    return default_tinymce;
}
function make_wysiwyg(inst)
{
    var type = inst.id.replace(/_[0-9]*$/,'');
    if (typeof usewrapper !== 'undefined' && usewrapper != false && (type == "tinymce"  || type == "section") )
    {

        if (! jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('div.main-wrp').length)
        {
            if (type == "section")
            {
                jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                .contents().find("body")
                .wrapInner('<div class="main-wrp '+ custom_section_css_class  +' "/>');
            } else if (type == "tinymce") {
                jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                .contents().find("body")
                .wrapInner('<div class="main-wrp '+custom_main_css_class  +'"/>');
            }
        }
        if (jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('div.main-wrp').length > 1)
        {
            jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('div.main-wrp').unwrap();
        }
    } else if (typeof usefooterwrapper !== 'undefined' && usefooterwrapper != false)
    {
        if (! jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('footer.footer').length)
        {
            jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                .contents().find("body")
                .wrapInner('<footer class="footer"/>');
        }
        if (jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('footer.footer').length > 1)
        {
            jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('footer.footer').unwrap();
        }
    } else if (typeof useheaderwrapper !== 'undefined' && useheaderwrapper != false)
    {
        if (! jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('header.header').length)
        {
            jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                .contents().find("body")
                .wrapInner('<header class="header"/>');
        }
        if (jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('header.header').length > 1)
        {
            jQuery(".mce-container-body iframe#"+inst.id +"_ifr").contents().find("body").find('header.header').unwrap();
        }
    }
    if (typeof usecustomwrapper !== 'undefined' && usecustomwrapper != false && (Array.isArray(usecustomwrapper) || typeof usecustomwrapper === "object") )
    {
        var element = usecustomwrapper['element'];
        var instance = usecustomwrapper['inst'];
        var class_css = usecustomwrapper['class_css'];

        if (type == instance)
        {
            if (! jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                    .contents()
                    .find("body")
                    .find(element+"."+class_css.join('.'))
                    .length
            )
            {
                jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                    .contents().find("body")
                    .wrapInner('<div class="'+class_css.join(' ')+'"/>');
            }
            if (jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                    .contents().find("body")
                    .find(element+"."+class_css.join('.'))
                    .length > 1)
            {
                jQuery(".mce-container-body iframe#"+inst.id +"_ifr")
                    .contents()
                    .find("body")
                    .find(element+"."+class_css.join('.'))
                    .unwrap();
            }
        }
    }
}
function elFinderBrowser (field_name, url, type, win)
{
    tinymce.activeEditor.windowManager.open(
    {
        file: default_tinymce.root+'/lib/elfinder.php',// use an absolute path!
        title: i18n.translate('Find Image').fetch(),
        width: 900,
        height: 450,
        inline: true,
        resizable: true
      }, {
        setUrl: function (url)
        {
          win.document.getElementById(field_name).value = url;
        }
      });
      return false;
}
function colPicker (callback,input)
{
    tinymce.activeEditor.windowManager.open(
    {
        file: default_tinymce.root+'/lib/colorpicker/connector.php',
        title: i18n.translate("Pick a color").fetch(),
        width: 80,
        height: 250,
        resizable: 'yes'
    }, {
        get_color: function()
        {
            return (input);
        },
        set_color: function(color)
        {
            callback(color);
        }
    });
}
function list_plugin()
{
    p="pagebreak, "+
    "table, "+
    "save, "+
    "emoticons, "+
    "insertdatetime, "+
    "preview, "+
    "contextmenu, "+
    "hr, "+
    "colorpicker, "+
    "wordcount, "+
    "noneditable, "+
    "visualchars, "+
    "image, "+
    "code, "+
    "advlist, "+
    "anchor, "+
    "autolink, "+
    "directionality, "+
    "fullscreen, "+
    "link, "+
    "lists, "+
    "media, "+
    "nonbreaking, "+
    "paste, "+
    "searchreplace, "+
    "tabfocus, "+
    "textcolor, "+
    "textpattern, "+
    "visualblocks, "+
    "save";
    return p;
}
function list_toolbar2()
{
    toolbar = "forecolor backcolor | alignleft aligncenter alignright alignjustify |  justifyleft justifycenter justifyright  justifyfull | table | paste | charmap | pagebreak | visualchars nonbreaking  | emoticons "
        if (typeof extra_tme_plugin !== "undefined" && typeof extra_tme_plugin === "object")
        {
            for(var i in extra_tme_plugin)
            {
                toolbar+= " | "+i;
            }

        }
        return toolbar;
}