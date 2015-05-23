jQuery(document).ready(function(){
    jQuery('#fileTree_1').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         jQuery('#rep_redim').val(dir);
     });
});
jQuery(document).ready(function(){
    jQuery('#fileTree_2').fileTree({
        root : '/',
        script : base_path+'/lib/jQueryFileTree/connector.php',
        folderEvent : 'click',
        expandSpeed : 750,
        collapseSpeed : 750,
        multiFolder : false
     }, function(file,dir)
     {
         jQuery('#rep_thumb').val(dir);
     });
});
jQuery(document).ready(function(){
    jQuery('button').button();
});