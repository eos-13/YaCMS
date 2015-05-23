jQuery(document).ready(function(){
    jQuery('#tabs').tabs();
    jQuery('button').button();
    jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});
    jQuery("select:not([multiple])").selectmenu({width:'400px'});
});

jQuery(document).ready(function(){
    default_tinymce = load_tiny_mce_var(all_tinymce,content_css_set,base_root_path);
    tinyMCE.init(default_tinymce);
});