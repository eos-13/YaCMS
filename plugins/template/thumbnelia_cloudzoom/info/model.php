<?php

$model = <<<EOF

{% extends "base.html" %}

{{ block.super }}
{%block plugin_js_code %}
jQuery(document).ready(function(){
    jQuery("#slider").Thumbelina({
        orientation:"{{plugins.thumbnelia_cloudzoom.orientation}}",
        easing:{{plugins.thumbnelia_cloudzoom.easing}},
        maxSpeed:{{plugins.thumbnelia_cloudzoom.maxSpeed}},
        {%if plugins.thumbnelia_cloudzoom.bwdBut == "null" %}
        \$bwdBut:null,
        {% else %}
        \$bwdBut:{{plugins.thumbnelia_cloudzoom.bwdBut | safe}},
        {%endif%}
        {%if plugins.thumbnelia_cloudzoom.fwdBut == "null" %}
        \$bwdBut:null,
        {% else %}
        \$fwdBut:{{plugins.thumbnelia_cloudzoom.fwdBut | safe}},
        {%endif%}
    });
    launch_cloudzoom();
});
function launch_cloudzoom()
{
    jQuery('.cloudzoom').CloudZoom({
        tintColor:"{{plugins.thumbnelia_cloudzoom.tintColor}}",
        tintOpacity:{{plugins.thumbnelia_cloudzoom.tintOpacity}},
        animationTime:{{plugins.thumbnelia_cloudzoom.animationTime}},
        galleryEvent:"{{plugins.thumbnelia_cloudzoom.galleryEvent}}",
        easeTime:{{plugins.thumbnelia_cloudzoom.easeTime}},
        zoomSizeMode:"{{plugins.thumbnelia_cloudzoom.zoomSizeMode}}",
        zoomMatchSize:{{plugins.thumbnelia_cloudzoom.zoomMatchSize}},
        zoomPosition:{{plugins.thumbnelia_cloudzoom.zoomPosition}},
        zoomOffsetX:{{plugins.thumbnelia_cloudzoom.zoomOffsetX}},
        zoomOffsetY:{{plugins.thumbnelia_cloudzoom.zoomOffsetY}},
        zoomFullSize:{{plugins.thumbnelia_cloudzoom.zoomFullSize}},
        uriEscapeMethod:"{{plugins.thumbnelia_cloudzoom.uriEscapeMethod}}",
        variableMagnification:{{plugins.thumbnelia_cloudzoom.variableMagnification}},
        startMagnification:"{{plugins.thumbnelia_cloudzoom.startMagnification}}",
        minMagnification:"{{plugins.thumbnelia_cloudzoom.minMagnification}}",
        maxMagnification:"{{plugins.thumbnelia_cloudzoom.maxMagnification}}",
        easing:{{plugins.thumbnelia_cloudzoom.easing}},
        lazyLoadZoom:{{plugins.thumbnelia_cloudzoom.lazyLoadZoom}},
        mouseTriggerEvent:"{{plugins.thumbnelia_cloudzoom.mouseTriggerEvent}}",
        disableZoom:{{plugins.thumbnelia_cloudzoom.disableZoom | safe}},
        galleryFade:{{plugins.thumbnelia_cloudzoom.galleryFade}},
        galleryHoverDelay:{{plugins.thumbnelia_cloudzoom.galleryHoverDelay}},
        permaZoom:{{plugins.thumbnelia_cloudzoom.permaZoom}},
        zoomWidth:{{plugins.thumbnelia_cloudzoom.zoomWidth}},
        zoomHeight:{{plugins.thumbnelia_cloudzoom.zoomHeight}},
        lensWidth:{{plugins.thumbnelia_cloudzoom.lensWidth}},
        lensHeight:{{plugins.thumbnelia_cloudzoom.lensHeight}},
        hoverIntentDelay:{{plugins.thumbnelia_cloudzoom.hoverIntentDelay}},
        hoverIntentDistance:{{plugins.thumbnelia_cloudzoom.hoverIntentDistance}},
        autoInside:{{plugins.thumbnelia_cloudzoom.autoInside}},
        disableOnScreenWidth:{{plugins.thumbnelia_cloudzoom.disableOnScreenWidth}},
        touchStartDelay:{{plugins.thumbnelia_cloudzoom.touchStartDelay}},
        appendSelector:"{{plugins.thumbnelia_cloudzoom.appendSelector}}",
        propagateGalleryEvent:{{plugins.thumbnelia_cloudzoom.propagateGalleryEvent}}
    });
}
{%endblock%}


{% block main %}

<p>{{main | safe}}</p>

<div  id="slider" class="{{plugins.thumbnelia_cloudzoom.orientation}}">
            <div class="thumbelina-but rwd {%if plugins.thumbnelia_cloudzoom.orientation == "vertical" %} vert  top  {% else  %} horiz  left {% endif %} ">{%if plugins.thumbnelia_cloudzoom.orientation == "vertical" %}&#708;{%else%}&#706{%endif%}</div>
            <ul>
                {% for i in thumb_image_path %}
                    <li><a href="{{conf.main_url_root}}/files/{{i.path}}">
                              <img {%if plugins.thumbnelia_cloudzoom.imagewidth != "0" %}
                                       width="{{plugins.thumbnelia_cloudzoom.imagewidth}}"
                                       {%endif %}
                                       {%if plugins.thumbnelia_cloudzoom.imageheight != "0" %}
                                       height="{{plugins.thumbnelia_cloudzoom.imageheight}}"
                                       {%endif %}
                                       src="{{conf.main_url_root}}/files/{{i.thb_path}}"
                                       title="{{i.title}}">
                           </a>
                    </li>
                {% endfor %}
            </ul>
            <div class="thumbelina-but fwd {%if plugins.thumbnelia_cloudzoom.orientation == "vertical" %} vert  bottom {% else %} horiz  right {% endif %}">{%if plugins.thumbnelia_cloudzoom.orientation == "vertical" %}&#709;{%else%}&#707{%endif%}</div>
        </div>
        <div>
        <img id="cloudzoom" class="cloudzoom" src="{{conf.main_url_root}}/files/{{thumb_image_path.0.thb_path}}" title="{{thumb_image_path.0.title}}"
             data-cloudzoom = "zoomImage: '{{conf.main_url_root}}/files/{{thumb_image_path.0.path}}'"
        />

        </div>
</div>
{% endblock %}

EOF;

