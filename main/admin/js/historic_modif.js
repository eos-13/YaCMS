function showChildGrid(row_id, uid)
{
    jQuery.ajax({
        url: "historic_modif",
        data:"action=extra_datas&page_refid="+page_refid+"&id="+uid,
        type: "POST",
        success: function (a)
        {
            var obj = jQuery.parseJSON(a);
            jQuery('#'+row_id).append(obj.html);
        }
    });
}
jQuery(document).ready(function(){
    jQuery('#jqGrid').jqGrid({
        url: 'historic_modif?action=list&page_refid='+page_refid,
        mtype: 'POST',
        datatype: 'json',
        colModel: [
                   {   label: i18n.translate('id').fetch(),
                       name: 'id',
                       key: true,
                       hidden: true,
                       width: 75,
                   },
                   {   label: i18n.translate('Name').fetch(),
                       name: 'user_name',
                       width: 150,
                       align:"center",
                       align: "center"

                   },
                   {   label: i18n.translate('Firstname').fetch(),
                       name: 'user_firstname',
                       width: 150,
                       align: "center"
                   },
                   {
                       label: i18n.translate('Date').fetch(),
                       name: 'date_modif',
                       width: 150,
                       align: "center"
                   },
                   {   label: i18n.translate('Type').fetch(),
                       name: 'type_modif',
                       align: "center"
                   },
                  ],
                  viewrecords: true,
                  width: 880,
                  height: 550,
                  rowNum: 20,
                  page:1,
                  shrinkToFit: true,
                  autowidth:true,
                  rownumbers: true,
                  hoverrows: true,
                  altRows: true,
                  caption:i18n.translate('Modifications').fetch(),
                  pager: '#jqGridPager',
                  subGridRowExpanded: showChildGrid,
                  subGrid: true
    });
    jQuery('#jqGrid').navGrid('#jqGridPager',
    {
        edit: false,
        add: false,
        del: false,
        search: true,
        refresh: true,
        view: true,
        position: 'left',
        cloneToTop: false,
    });
});
jQuery(document).ready(function()
{
    jQuery(window).bind('resize', function()
    {
        var width = jQuery(".admin-wrapper").width() - 3;
        jQuery("#jqGrid").setGridWidth(width);
    }).trigger('resize');
});