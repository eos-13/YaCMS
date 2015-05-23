var instance;

var _addEndpoints = function (toId, sourceAnchors, targetAnchors)
{
    for (var i = 0; i < sourceAnchors.length; i++)
    {
        var sourceUUID = toId + sourceAnchors[i];
        sourceEndpoint.parameters =  { "endpoint_pos": sourceAnchors[i] },
        instance.addEndpoint(
                "flowchart" + toId,
                sourceEndpoint,
                {
                    anchor: sourceAnchors[i],
                    uuid: sourceUUID,
                }
        );
    }
    for (var j = 0; j < targetAnchors.length; j++)
    {
        var targetUUID = toId + targetAnchors[j];
        instance.addEndpoint(
                "flowchart" + toId,
                targetEndpoint,
                {
                    anchor: targetAnchors[j],
                    uuid: targetUUID
                }
        );
    }
};
var connectorPaintStyle =
{
    lineWidth: 1,
    strokeStyle: "#0073ea",
    joinstyle: "round",
    outlineColor: "white",
    outlineWidth: 1
},
connectorHoverStyle =
{
    lineWidth: 1,
    strokeStyle: "#ff3a00",
    outlineWidth: 1,
    outlineColor: "white"
},
endpointHoverStyle =
{
    fillStyle: "#ff3a00",
    strokeStyle: "#ff3a00"
},
targetEndpoint = {
    endpoint: "Dot",
    paintStyle: {
        fillStyle: "#0073ea",
        radius: 6
    },
    hoverPaintStyle: endpointHoverStyle,
    maxConnections: 1,
    dropOptions: {
        hoverClass: "hover",
        activeClass: "active"
    },
    isTarget: true,
};
sourceEndpoint = {
    endpoint: "Dot",
    paintStyle: {
        strokeStyle: "#0073ea",
        fillStyle: "transparent",
        radius: 3,
        lineWidth: 2
    },
    isSource: true,
    connector: [ "Flowchart",
        {
            stub: [20, 30],
            gap: 5,
            cornerRadius: 15,
            alwaysRespectStubs: true
        }
    ],
    connectorStyle: connectorPaintStyle,
    hoverPaintStyle: endpointHoverStyle,
    maxConnections: -1,
    connectorHoverStyle: connectorHoverStyle,
    dragOptions: {},
};

jQuery(document).ready(function()
{
    jsPlumb.ready(function ()
    {
        jsPlumb.setContainer("flowchart");
        instance = jsPlumb.getInstance({
            DragOptions: { cursor: 'pointer',
                           zIndex: 2000
            },
            ConnectionOverlays: [
                                 [ "Arrow",
                                   {
                                     location: 1,
                                     width: 10,
                                     length: 10
                                   }
                                 ],
                                 [ "Label",
                                   {
                                     location: 0.1,
                                   }
                                 ]
                                ],
                                Container: "flowchart"
        });

        init = function (connection) {};
        instance.batch(function ()
        {
            _addEndpoints("Window"+rootId, [ "BottomCenter" ], []);
            instance.bind("connection", function (connInfo, originalEvent)
            {
                init(connInfo.connection);
            });
            instance.draggable(jsPlumb.getSelector(".flowchart .window:not(.root)"), { grid: [5, 5], stop: storeCoord });
            instance.bind("dblclick", function (conn, originalEvent)
            {
                var s = jQuery("#"+conn.sourceId).attr('data-test');
                var t = jQuery("#"+conn.targetId).attr('data-test');
                instance.detach(conn);
                jQuery.ajax(
                {
                    data: "action=delConnection&s="+s+"&t="+t,
                    url: "hierarchie",
                    type: "POST",
                    dataType:"json",
                    success: function(a)
                    {
                        displayMsg(a.message);
                    }
                });
            });
            instance.bind("connectionDrag", function (connection) {});
            instance.bind("connectionDragStop", function (connection)
            {
                if (null == connection.connector)
                {
                    var s = jQuery("#"+connection.sourceId).attr('data-test');
                    var t = jQuery(connection.target).attr('data-test');
                    var sus = jQuery(connection.suspendedElement).attr('data-test');
                    if (sus != t)
                    {
                        jQuery.ajax(
                        {
                            data: "action=delConnection&s="+s+"&t="+t+"&sus="+sus,
                            url: "hierarchie",
                            type: "POST",
                            dataType:"json",
                            success: function(a){
                                displayMsg(a.message);
                            }
                        });
                    }
                } else {
                    var s = jQuery("#"+connection.sourceId).attr('data-test');
                    var sus = jQuery(connection.suspendedElement).attr('data-test');
                    var t = jQuery("#"+connection.targetId).attr('data-test');
                    var endpoint_pos = connection.getParameter("endpoint_pos");
                    if (sus != t)
                    {
                        if (sus > 0)
                        {
                            jQuery.ajax(
                            {
                                data: "action=moveConnection&s="+s+"&t="+t+"&sus="+sus+"&endpoint_pos="+endpoint_pos,
                                url: "hierarchie",
                                type: "POST",
                                dataType:"json",
                                success: function(a)
                                {
                                    displayMsg(a.message);
                                }
                            });
                        } else {
                            jQuery.ajax(
                            {
                                data: "action=newConnection&s="+s+"&t="+t+"&sus="+sus+"&endpoint_pos="+endpoint_pos+"&left="+jQuery("#"+connection.targetId).css('left')+"&top="+jQuery("#"+connection.targetId).css('top'),
                                url: "hierarchie",
                                type: "POST",
                                dataType:"json",
                                success: function(a)
                                {
                                    displayMsg(a.message);
                                }
                            });
                        }
                    }
                }
            });
            instance.bind("connectionMoved", function (params) {});
        });
        jsPlumb.fire("jsPlumbLoaded", instance);
    });
});

function displayMsg(msg)
{
    jQuery.growl({
        title: i18n.translate("Résultat").fetch(),
        message: msg,
        location: 'tr',
    });
}
jQuery(window).resize(function()
{
    jsPlumb.repaintEverything();
});
window.setZoom = function(zoom, instance, transformOrigin, el)
{
      transformOrigin = transformOrigin || [ 0.5, 0.5 ];
      instance = instance || jsPlumb;
      el = el || instance.getContainer();
      var p = [ "webkit", "moz", "ms", "o" ],
          s = "scale(" + zoom + ")",
          oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";

      for (var i = 0; i < p.length; i++) {
        el.style[p[i] + "Transform"] = s;
        el.style[p[i] + "TransformOrigin"] = oString;
      }
      el.style["transform"] = s;
      el.style["transformOrigin"] = oString;
      instance.setZoom(zoom);
};
function zoom(factor)
{
    window.setZoom(factor,instance,[0,0],jQuery('#flowchart')[0]);
    jsPlumb.setContainer("flowchart");
    var width=jQuery('#zoom').width();
        width = width / factor;
    var height=jQuery('#zoom').height();
        height = height / factor;

    jQuery("#flowchart").css({
        "-webkit-transform":"scale("+factor+")",
        "-moz-transform":"scale("+factor+")",
        "-ms-transform":"scale("+factor+")",
        "-o-transform":"scale("+factor+")",
        "transform":"scale("+factor+")",
        "width": width,
        "height": height
    });
    instance.setZoom(factor);
}
jQuery(document).ready(function()
{
    jQuery("#slider").slider(
    {
        orientation: "vertical",
        range: "min",
        min: 0,
        max: 1.05,
        value: 1,
        step: 0.1,
        slide: function( event, ui )
        {
            zoom( ui.value );
        }
    });
});
function storeCoord(e,u)
{
    var top = u.position.top;
    var left = u.position.left;
    var id = jQuery(e.target).attr('data-test');
    jQuery.ajax({
        data: "action=storePos&id="+id+"&top="+top+"&left="+left,
        url: "hierarchie",
        type: "POST",
        dataType:"json",
        success: function(a)
        {
            displayMsg(a.message);
        }
    });
}
jQuery(document).ready(function(){
    jQuery('div.flowchart').each(function()
    {
        jQuery(this).dblclick(function()
        {
            var id = jQuery(this).attr('data-test');
            if (typeof id !== "undefined")
            {
                location.href="edit_page?id="+id;
            }
        });
    })
});