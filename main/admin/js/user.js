var mode_dialog="edit";
function valideDoubleLogin(val,col,colName)
{
    var grid = jQuery("#jqGrid");
    var selRowId = grid.jqGrid ('getGridParam', 'selrow');
    var id = grid.jqGrid ('getCell', selRowId, 'id');
    var data = "oper="+mode_dialog+"&id="+id+"&val="+val+"&col="+colName;
    var json =  [false, col+":"+i18n.translate("Unknow Error").fetch()];
    jQuery.ajax({
        url: "user?action=valid",
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
function valideDoubleMail(val,col)
{
    var grid = jQuery("#jqGrid");
    var selRowId = grid.jqGrid ('getGridParam', 'selrow');
    var id = grid.jqGrid ('getCell', selRowId, 'id');

    var data = "oper="+mode_dialog+"&id="+id+"&val="+val+"&col="+col;
    var json =  [false, i18n.translate("Unknow Error").fetch()];
    jQuery.ajax({
        url: "user?action=valid",
        data: data,
        type: "POST",
        dataType: "json",
        async: false,
        error: function()
        {
            json =  [false, i18n.translate("Unknow Error").fetch()]
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
        width:500,
        left:350,
        closeAfterAdd: true,
    });
}
function editRow() {
    var grid = jQuery("#jqGrid");
    var rowKey = grid.getGridParam("selrow");
    if (rowKey) {
        grid.editGridRow(rowKey,
        {
            closeAfterEdit: true,
            recreateForm: true,
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
function showChildGrid(row_id, uid)
{
    jQuery.ajax({
        url: "user",
        data:"action=extra_datas&id="+uid,
        type: "POST",
        success: function (a)
        {
            var obj = jQuery.parseJSON(a);
            html = "<div><div id='tabs'>";
            html += "<ul>";
            html += "<li><a href='#tabs-1'>"+i18n.translate("Infos").fetch()+"</a></li>";
            if (obj.group)
            {
                html += "<li><a href='#tabs-2'>"+i18n.translate("Groupes").fetch()+"</a></li>";
            }
            if (obj.page)
            {
                html += "<li><a href='#tabs-3'>"+i18n.translate("Publications").fetch()+"</a></li>";
            }
            if (obj.comment)
            {
                html += "<li><a href='#tabs-4'>"+i18n.translate("Commentaires").fetch()+"</a></li>";
            }
            html += "</ul>";
            html += "<div id='tabs-1'>";
            html += "<ul>";
            html += "<li>"+i18n.translate("MD5").fetch()+": "+obj.extra[0].md5+"</li>";
            html += "<li>"+i18n.translate("Dernière connexion").fetch()+": "+obj.extra[0].last_login+"</li>";
            html += "<li>"+i18n.translate("Profile").fetch()+": "+obj.extra[0].profile_public+"</li>";
            html += "<li>"+i18n.translate("Description").fetch()+": "+obj.extra[0].description+"</li>";
            html += "</ul><br/>";
            html += "</div>";

            if (obj.group)
            {
                html += "<div id='tabs-2'>";
                html += "<table CELLPADDING=5>";
                html += "<thead><tr><td colspan='2'>"+i18n.translate("Groupe").fetch()+"</td></tr></thead>";
                html += "<tbody>";
                for (var i=0;i<obj.group.length;i++)
                {
                    html += "<tr><td>"+obj.group[i].name+"</td><td><button onclick='remove_group("+uid+","+obj.group[i].id+")'>"+i18n.translate("Remove").fetch()+"</button></td>";
                }
                html += "</tbody>";
                html += "</table>";
                html += "</div>";
            }

            if (obj.page)
            {
                html += "<div id='tabs-3'>";
                html += "<table CELLPADDING=5>";
                html += "<thead>";
                html += "<tr><td colspan='5'>"+i18n.translate("Publication").fetch()+"</td></tr>";
                html += "<tr>";
                html += "<th>"+i18n.translate("Titre").fetch()+"</th>";
                html += "<th>"+i18n.translate("Créé le").fetch()+"</td>";
                html += "<th>"+i18n.translate("Dernière maj").fetch()+"</td>";
                html += "<th>"+i18n.translate("Action").fetch()+"</td>";
                html += "</tr>";
                html += "</thead>";
                html += "<tbody>";

                for (var i=0;i<obj.page.length;i++)
                {
                    html += "<tr><td><a href='"+obj.page[i].url+"'>"+obj.page[i].title+"</a></td>";
                    html += "<td>"+obj.page[i].date_creation+"</td>";
                    html += "<td>"+obj.page[i].date_derniere_maj+"</td>";
                    html += "<td><button onclick='location.href=\"edit_page?id="+obj.page[i].id+"\"'>"+i18n.translate("Editer").fetch()+"</button></td>";
                    html += "</tr>";
                }
                html += "</tbody>";
                html += "</table>";
                html += "</div>";
            }
            if (obj.comment)
            {
                html += "<div id='tabs-4'>";
                html += "<table CELLPADDING=5>";
                html += "<thead>";
                html += "<tr><td colspan='4'>"+i18n.translate("Commentaires").fetch()+"</td></tr>";
                html += "<tr>";
                html += "<th>"+i18n.translate("Page").fetch()+"</th>";
                html += "<th>"+i18n.translate("Commentaire le").fetch()+"</th>";
                html += "<th>"+i18n.translate("Titre").fetch()+"</th>";
                html += "<th>"+i18n.translate("Validation").fetch()+"</th>";
                html += "</tr>";
                html += "</thead>";
                html += "<tbody>";
                for (var i=0;i<obj.comment.length;i++)
                {
                    html += "<tr>";
                    html += "<td><a href='"+obj.comment[i].url+"'>"+obj.comment[i].title+"</a></td>";
                    html += "<td>"+obj.comment[i].date_creation+"</td>";
                    html += "<td>"+obj.comment[i].ctitle+"</td>";
                    html += "<td>"+obj.comment[i].valid+"</td>";
                    html += "</tr>";
                }
                html += "</tbody>";
                html += "</table>";
                html += "</div></div>";
            }
            jQuery('#'+row_id).append(html);
            jQuery('#'+row_id).find('button').button();
            jQuery('#'+row_id).find('#tabs').tabs();
        }
    });
}
function createPassCheck(el)
{
    var passCheck = jQuery("<tr></tr>").addClass("FormData")
    .attr({
        id: "tr_passwordCheck",
        rowpos: 20
    });
    var passCheckLabelTd = jQuery("<td></td>").addClass("CaptionTD").text(i18n.translate("Password Check").fetch());
    passCheck.append(passCheckLabelTd);
    var passCheckTd = jQuery("<td>&nbsp;</td>").addClass("DataTD");
    passCheck.append(passCheckTd);
    var passCheckInput = jQuery("<input></input>")
        .addClass("FormElement ui-widget-content ui-corner-all")
        .attr({
            id: "passwordCheck",
            name: "passwordCheck",
            role: "textbox",
            type: "password"
        });
    passCheckTd.append(passCheckInput);
    var tbodyEl = el.parentNode.parentNode.parentNode;
    tbodyEl.appendChild(passCheck[0]);
}
function customPassCheck(cellvalue, cellname)
{
    var passCheckVal = jQuery("#tr_passwordCheck input").val()
    if (
            (cellvalue == "" && passCheckVal == "")
            ||
            cellvalue == passCheckVal
    ) {
        return [true, ""];
    }
    return [false, i18n.translate("Password and password check don't match.").fetch()];
}
function customPassFormat(cellvalue, options, rowObject) {
    jQuery('#TblGrid_jqGrid').find('#pass').val("");
    return '';
}
jQuery(document).ready(function(){
    jQuery('#jqGrid').jqGrid({
        url: 'user?action=list',
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
                   {   label: i18n.translate('Login').fetch(),
                       name: 'login',
                       width: 75,
                       editable: true,
                       align:"center",
                       editrules: {
                           required: true,
                           custom_func:function(val,col)
                           {
                               return valideDoubleLogin(val,col,"login");
                           },
                           custom: true,
                       }
                   },
                   {   label: i18n.translate('Password').fetch(),
                       name: 'pass',
                       width: 75,
                       editable: true,
                       editrules: {
                           edithidden: true,
                           custom:true,
                           custom_func:function(val,col)
                           {
                               return customPassCheck(val,col,"pass");
                           },
                       },
                       hidden: true,
                       edittype: 'password',
                       formatter: customPassFormat,
                       editoptions: {
                           dataInit: createPassCheck
                       },
                   },
                   {
                       label: i18n.translate('Name').fetch(),
                       name: 'name',
                       width: 150,
                       editable: true,
                       align:"center",
                       editrules: {
                           required: true
                       }
                   },
                   {   label: i18n.translate('Firstname').fetch(),
                       name: 'firstname',
                       width: 150,
                       editable: true,
                       align:"center",
                       editrules: {
                           required: true
                       },
                   },
                   {   label: i18n.translate('Email').fetch(),
                       name: 'email',
                       width: 150,
                       align:"center",
                       editable: true,
                       editrules: {
                           email: true,
                           required: true,
                           custom:true,
                           custom_func:function(val,col)
                           {
                               return valideDoubleMail(val,col,"email");
                           },
                       }
                   },
                   {   label:i18n.translate('Description').fetch(),
                       name: 'description',
                       editable: true,
                       edittype: 'textarea'
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
                       align:"center",
                       formatter: "select"
                   },
                   {   label:i18n.translate('Verrouillé').fetch(),
                       name: 'is_locked',
                       width: 55,
                       editable: true,
                       editrules: {
                           required: true
                       },
                       edittype: 'select',
                       sortable: false,
                       stype:'select',
                       searchoptions:{
                           sopt: ['eq','ne'],
                           value:{0:i18n.translate('Non').fetch(),1:i18n.translate('Vérouillé').fetch()}
                       },
                       editoptions: {
                           value:{"0":i18n.translate('Non').fetch(),"1":i18n.translate('Vérouillé').fetch()},
                       },
                       align:"center",
                       formatter: "select"
                   }
                  ],
                  viewrecords: true,
                  width: 880,
                  editurl: 'user?action=edit',
                  height: 550,
                  rowNum: 20,
                  page:1,
                  shrinkToFit: true,
                  autowidth:true,
                  rownumbers: true,
                  hoverrows: true,
                  altRows: true,
                  caption: i18n.translate('User').fetch(),
                  pager: '#jqGridPager',
                  subGridRowExpanded: showChildGrid,
                  gridComplete: initGrid,
                  subGrid: true
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
    jQuery('#jqGrid').bind('jqGridAddEditBeforeShowForm',
        function(a,b,c)
        {
            b.find('#pass').attr('autocomplete','off');
        });

    jQuery('#jqGrid').bind('jqGridAddEditAfterShowForm',
        function(a,b,c)
        {
            b.find('#pass').val('');
        });
});
function remove_group(uid,gid)
{
    var g = jQuery('<input>').prop('type','hidden').prop('name','rem_group').val(gid);
    var u = jQuery('<input>').prop('type','hidden').prop('name','rem_members[]').val(uid);
    var a = jQuery('<input>').prop('type','hidden').prop('name','action').val('rem_members');
    var f = jQuery('<form>').prop('action','user').prop('method','post').prop('id','tmpForm')
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