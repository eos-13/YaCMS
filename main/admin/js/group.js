var mode_dialog="edit";
function valideDoubleName(val,colId,col)
{
    var grid = jQuery("#jqGrid");
    var selRowId = grid.jqGrid ('getGridParam', 'selrow');
    var id = grid.jqGrid ('getCell', selRowId, 'id');

    var data = "oper="+mode_dialog+"&id="+id+"&val="+val+"&col="+col;
    var json =  [false, col+": "+i18n.translate("Unknow Error").fetch()];
    jQuery.ajax({
        url: "group?action=valid",
        data: data,
        type: "POST",
        dataType: "json",
        async: false,
        error: function()
        {
            json =  [false, col+": "+i18n.translate("Unknow Error").fetch()]
        },
        success: function (pJson)
        {
            json= pJson;
        }
    });
    return json;
}

function initGrid() {
        jQuery(this).contextMenu('contextMenu', {
            bindings: {
                'edit': function (t) {
                    mode_dialog='edit';
                    editRow();
                },
                'add': function (t) {
                    mode_dialog='add';
                    addRow();
                },
                'del': function (t) {
                    delRow();
                }
            },
            onContextMenu: function (event, menu) {
                var rowId = jQuery(event.target).parent("tr").attr("id")
                var grid = jQuery("#jqGrid");
                grid.setSelection(rowId);
                return true;
            }
        });
}
function addRow() {
    var grid = jQuery("#jqGrid");
    grid.editGridRow("new",
    {
        recreateForm: true,
        closeAfterAdd: true,
        left:350,
        width:500,
    });
}

function editRow() {
    var grid = jQuery("#jqGrid");
    var rowKey = grid.getGridParam("selrow");
    if (rowKey) {
        grid.editGridRow(rowKey,
        {
            recreateForm: true,
            closeAfterEdit: true,
            left:350,
            width:500,
        });
    }
    else {
        alert(i18n.translate("No rows are selected").fetch());
    }
}

function delRow() {
    var grid = jQuery("#jqGrid");
    var rowKey = grid.getGridParam("selrow");
    if (rowKey) {
        grid.delGridRow(rowKey);
    }
    else {
        alert(i18n.translate("No rows are selected").fetch());
    }
}
function formatImage(cellValue, options, rowObject)
{
    var imageHtml = "<center><img width=96 src='" + cellValue + "' originalValue='" + cellValue + "' /></center>";
    return imageHtml;
}

function showChildGrid(parentRowID, parentRowKey)
{
    jQuery.ajax({
        url: "group",
        type: "POST",
        data: "id="+parentRowKey+"&action=list_members",
        success: function (a) {
            var obj = jQuery.parseJSON(a);
            jQuery('#'+parentRowID).append("<div class='ui-widget ui-widget-header subgrid-title'>"+i18n.translate("Membres de ce groupe").fetch()+":</div><br/><table>");
            for (var i=0;i<obj.length;i++)
            {
                jQuery('#'+parentRowID).append('<tr><td><div style="display: inline; margin-right:10px;min-width:150px;width:150px;max-width:150px;">'+obj[i].name+' '+obj[i].firstname+'</div></td><td><button onClick="remove_member('+parentRowKey+','+obj[i].id+')">'+i18n.translate("Remove").fetch()+'</td></tr>');
            }
            jQuery('#'+parentRowID).append("</table>");
            jQuery('#'+parentRowID).find('button').button();
        }
    });
}

jQuery(document).ready(function(){
    jQuery('#jqGrid').jqGrid({
        url: 'group?action=list',
        mtype: 'POST',
        datatype: 'json',
        colModel: [
                   {   label: i18n.translate('id').fetch(),
                       name: 'id',
                       key: true,
                       hidden: true,
                       width: 75,
                       editable: false
                   },
                   {
                       label: i18n.translate('Name').fetch(),
                       name: 'name',
                       width: 150,
                       align:"center",
                       editable: true,
                       editrules: {
                           required: true,
                           custom:true,
                           custom_func: function(val,col)
                           {
                               return valideDoubleName(val,col,'name');
                           }
                       }
                   },
                   {   label: i18n.translate('Email').fetch(),
                       name: 'email',
                       align:"center",
                       width: 150,
                       editable: true,
                       editrules: {
                           email: true,
                           required: false,
                       }
                   },
                   {   label:i18n.translate('Description').fetch(),
                       name: 'description',
                       editable: true,
                       edittype: 'textarea',
                       shrinkToFit: true,
                   },
                   {   label:i18n.translate('Avatar').fetch(),
                       name: 'avatar_url',
                       search: false,
                       sortable: false,
                       formatter: formatImage,
                       width: 150
                   },
                   {   label:i18n.translate('Active').fetch(),
                       name: 'active',
                       width: 55,
                       editable: true,
                       align:"center",
                       editrules: {
                           required: true
                       },
                       edittype: 'select',
                       sortable: false,
                       stype:'select',
                       searchoptions:{
                           sopt: ['eq','ne'],
                           value:{0:i18n.translate('Inactif').fetch(),1:i18n.translate('Actif').fetch()}
                       },
                       editoptions: {
                           value:{"0":i18n.translate('Inactif').fetch(),"1":i18n.translate('Actif').fetch()},
                       },
                       formatter: "select"
                   }
                  ],
                  viewrecords: true,
                  editurl: 'group?action=edit',
                  height: 550,
                  shrinkToFit: true,
                  autowidth:true,
                  rowNum: 20,
                  page:1,
                  rownumbers: true,
                  hoverrows: true,
                  altRows: true,
                  caption: i18n.translate('Group').fetch(),
                  pager: '#jqGridPager',
                  subGridRowExpanded: showChildGrid,
                  gridComplete: initGrid,
                  subGrid: true,
    });
    jQuery('#jqGrid').navGrid('#jqGridPager',
    {
        edit: true,
        add: true,
        del: true,
        search: true,
        refresh: true,
        view: true,
        position: 'left',
        cloneToTop: false,
    },{
        recreateForm: true,
        closeAfterEdit: true,
        left:350,
        width:500,
        beforeShowForm: function(a,b){ mode_dialog = b; },
    },{
        recreateForm: true,
        closeAfterAdd: true,
        left:350,
        width:500,
        beforeShowForm: function(a,b){  mode_dialog = b; },

    });

});
jQuery(document).ready(function()
{
    jQuery('button').button();
    jQuery("select[multiple]").multiselect({header: i18n.translate("Choose an Option!").fetch()});
    jQuery("select#rem_group").selectmenu({width:231, change: function()
    {
        id = jQuery(this).val();
        jQuery.ajax({
            url:"group",
            data:"action=list_members&id="+id,
            type:"post",
            success:function(a)
            {
                jQuery('#rem_members').find('option').remove();
                var obj = jQuery.parseJSON(a);
                for (var i=0;i<obj.length;i++)
                {
                    jQuery('#rem_members').append('<option value="'+obj[i].id+'">'+obj[i].name+' '+obj[i].firstname+'</option>');
                }
                jQuery('#rem_members').multiselect('refresh');
            }
        });

    }
    });
});

function remove_member(gid,uid)
{
    var g = jQuery('<input>').prop('type','hidden').prop('name','rem_group').val(gid);
    var u = jQuery('<input>').prop('type','hidden').prop('name','rem_members[]').val(uid);
    var a = jQuery('<input>').prop('type','hidden').prop('name','action').val('rem_members');
    var f = jQuery('<form>').prop('action','group').prop('method','post').prop('id','tmpForm')
                            .append(g)
                            .append(a)
                            .append(u);
    jQuery('body').append(f);
    jQuery('#tmpForm').submit();
}
jQuery(document).ready(function()
{
    jQuery(window).bind('resize', function()
    {
        var width = jQuery(".admin-wrapper").width() - 3;
        jQuery("#jqGrid").setGridWidth(width);

    }).trigger('resize');
});