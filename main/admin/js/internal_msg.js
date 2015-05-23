
function initGrid() {
        jQuery(this).contextMenu('contextMenu', {
            bindings: {
                'cloture': function (t) {
                    cloture();
                },
            },
            onContextMenu: function (event, menu) {
                var rowId = jQuery(event.target).parent("tr").attr("id")
                var grid = jQuery("#jqGrid");
                grid.setSelection(rowId);
                return true;
            }
        });
}


function showChildGrid(parentRowID, parentRowKey) {
    var msgid = parentRowKey;
    jQuery.ajax({
        url: "internal_msg",
        cache: true,
        dataType: "html",
        content_type: "application/x-www-form-urlencoded; charset=UTF-8",
        type: "POST",
        data: 'action=get_content&id='+parentRowKey,
        success: function (html) {
            jQuery("#" + parentRowID).append(html);
            jQuery("#" + parentRowID).find('button').button();
            tinyMCE.baseURL = base_path+"/admin/js/tinymce/";// trailing slash important
            var tinyMCEPreInit = {
                    suffix: '',
                    base: base_path+"/admin/js/tinymce/",
                    query: ''
            };
            jQuery("#" + parentRowID).find('textarea').tinymce({
                // Location of TinyMCE script
                script_url : base_path+'/admin/js/tinymce/tinymce.min.js',
                // General options
                theme : "modern",
                plugins : "emoticons,hr,visualchars,nonbreaking",
                menubar: false
            });
            jQuery('#'+parentRowID).find('#sendResponse').click(function()
            {
                var data = jQuery('#'+parentRowID).find('textarea:tinymce').tinymce().getContent();
                jQuery.ajax({
                    url:'internal_msg',
                    type: 'POST',
                    data: 'data='+data+"&id="+msgid+"&action=send_response",
                    success: function(res)
                    {
                        jQuery("#jqGrid").trigger("reloadGrid");
                        jQuery.growl({
                            title: i18n.translate("Résultat").fetch(),
                            message: res,
                            location: "tr",
                            duration: 3200
                        });
                    }
                });
            });

        }
    });
}
function cloture(id)
{
    jQuery.ajax({
        url: "internal_msg",
        type: "POST",
        data: 'action=cloture&id='+id,
        success: function (html) {
            jQuery("#list").trigger("reloadGrid");
        }
    });
}
jQuery(document).ready(function(){
    jQuery('#jqGrid').jqGrid({
        url: 'internal_msg?action=list',
        mtype: 'POST',
        datatype: 'json',
        colNames:[i18n.translate("Id").fetch(),i18n.translate("Titre").fetch(),i18n.translate("Status").fetch(),i18n.translate("Type").fetch(),i18n.translate("Nom").fetch(),i18n.translate("Prénom").fetch(),i18n.translate("Date").fetch(),i18n.translate("hdate").fetch(),i18n.translate("Actions").fetch()],
        colModel:[
            { name:'id',index:'id', width:55},
            { name:'title',index:'title', width:90},
            { name:'internal_msg_status.name',index:'internal_msg_status.name', width:100, stype: 'select', searchoptions:{sopt:['eq','ne'], value:listAllStatus},},
            { name:'internal_msg_type.name',index:'internal_msg_type.name', width:100, stype: 'select', searchoptions:{sopt:['eq','ne'], value:listAllType},},
            { name:'user.name',index:'user.name', width:100},
            { name:'user.firstname',index:'user.firstname', width:100},
            { name:'internal_msg.date_create',index:'internal_msg.date_create', sorttype:"date",formatter:'date', formatoptions: { srcformat:'Y-m-d h:i:s', newformat:'d/m/Y h:i' }, searchoptions:{dataInit: function (elem) { jQuery(elem).datepicker();}}, width:100},
            { name:'internal_msg.date_create_group',index:'internal_msg.date_create', sorttype:"date", hidden:true, formatter:'date', searchoptions:{dataInit: function (elem) { jQuery(elem).datepicker();}}, width:100},
            {
                    label: "Actions",
                    name: "actions",
                    width: 100,
                    sortable: false,
            },
        ],
        viewrecords: true,
        width: 880,
        editurl: 'internal_msg?action=edit',
        height: 550,
        rowNum: 20,
        page:1,
        altRows:true,
        autowidth: true,
        forceFit : true,
        sortname: 'id',
        scroll: 1,
        grouping: true,
            groupingView: {
                groupField: ["internal_msg.date_create_group"],
                groupColumnShow: [true],
                groupText: ["<b>{0}</b>"],
                groupOrder: ["desc"],
                groupSummary: [false],
                groupCollapse: false
            },
        rownumbers: true,
        hoverrows: true,
        altRows: true,
        caption: i18n.translate('Internal_msg').fetch(),
        pager: '#jqGridPager',
        subGridRowExpanded: showChildGrid,
        gridComplete: initGrid,
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
    },{
        recreateForm: true,
        left:350,
        closeAfterEdit: true,
        width:500,
        beforeShowForm: function(a,b){ mode_dialog = b; },

    },{
        recreateForm: true,
        width:500,
        left:350,
        closeAfterAdd: true,
        beforeShowForm: function(a,b){  mode_dialog = b; },


    });
});
jQuery(document).ready(function(){
    jQuery("#changegroup").selectmenu({
        width:200,
        change: function(){
        var vl = jQuery(this).val();
            if(vl) {
                if(vl === "clear") {
                    jQuery("#jqGrid").jqGrid('groupingRemove',true);
                } else {
                    jQuery("#jqGrid").jqGrid('groupingGroupBy',vl);
                }
            }
        },
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