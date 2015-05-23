function initGrid() {
        jQuery(this).contextMenu('contextMenu', {
            bindings: {
                'spam': function (t) {
                    spamRow();
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

function spamRow() {
    var grid = jQuery("#jqGrid");
    var rowKey = grid.getGridParam("selrow");
    if (rowKey) {
        jQuery.ajax({
            url: "akismet?id="+rowKey,
            type: "post",
            data: 'action=spam',
            success: function(a)
            {
                jQuery("#jqGrid").trigger( 'reloadGrid' );
            }
        });
    }
    else {
        alert(i18n.translate("No rows are selected").fetch());
    }
}

function showChildGrid(parentRowID, parentRowKey)
{

    jQuery.ajax({
        url: "akismet",
        type: "POST",
        data: "action=detail&id="+parentRowKey,
        success: function (html)
        {
            jQuery("#" + parentRowID).append(html);
        }
    });
}

jQuery(document).ready(function()
{
    if (jQuery('#jqGrid').length)
    {
        jQuery('#jqGrid').jqGrid({
            url: 'akismet?action=list',
            mtype: 'POST',
            datatype: 'json',
            colModel: [
                       {   label: i18n.translate('id').fetch(),
                           name: 'commentaire.id',
                           key: true,
                           hidden: true,
                           width: 75,
                           editable: false
                       },
                       {   label: i18n.translate('Author').fetch(),
                           name: 'commentaire.author',
                           width: 75,
                       },
                       {
                           label:i18n.translate('url').fetch(),
                           name: 'page.url',
                           search: true,
                           sortable: true,
                           width: 150
                       },
                       {
                           label:i18n.translate('title').fetch(),
                           name: 'commentaire.title',
                           search: true,
                           sortable: true,
                           width: 150
                       },
                       {
                           label:i18n.translate('content').fetch(),
                           name: 'commentaire.content',
                           search: true,
                           sortable: false,
                           width: 150
                       },
                       {
                           label:i18n.translate('Valide').fetch(),
                           name: 'commentaire.valid',
                           width: 35,
                           sortable: false,
                           stype:'select',
                           searchoptions:{
                               sopt: ['eq','ne'],
                               value:{0:i18n.translate('Inactif').fetch(),1:i18n.translate('Actif').fetch()}
                           },
                           formatter: formatValid
                       }
                      ],
                      viewrecords: true,
                      width: 880,
                      editurl: 'akismet?action=edit',
                      height: 550,
                      rowNum: 20,
                      page:1,
                      rownumbers: true,
                      hoverrows: true,
                      sortname: "id",
                      altRows: true,
                      caption: i18n.translate('Commentaires').fetch(),
                      pager: '#jqGridPager',
                      subGrid: true,
                      subGridRowExpanded: showChildGrid,
                      gridComplete: initGrid,
                      grouping: true,
                      groupingView:
                      {
                          groupField: ["page.url"],
                          groupColumnShow: [true],
                          groupText: ["<b>{0}</b>"],
                          groupOrder: ["asc"],
                          groupSummary: [false],
                          groupCollapse: false
                      }
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
        jQuery("#chngroup").selectmenu(
        {
            width: 400,
            change:function()
            {
                var vl = jQuery(this).val();
                if(vl)
                {
                    if(vl === "clear")
                    {
                        jQuery("#jqGrid").jqGrid('groupingRemove',true);
                        jQuery("#jqGrid").trigger( 'reloadGrid' );
                    } else {
                        jQuery("#jqGrid").jqGrid('groupingGroupBy',vl);
                    }
                }
            }
        });
    }
});
function formatValid(cellValue, options, rowObject)
{
    var html ="<center>";
    if (cellValue == 1) { html += "<span class='ui-icon ui-icon-circle-plus'></span>"; }
    if (cellValue == 0) { html += "<span class='ui-icon ui-icon-circle-close'></span>"; }
    html += "</center>";
    return html
}