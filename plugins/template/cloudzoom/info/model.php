<?php

$model = <<<EOD

{% extends "base.html" %}

{%block plugin_js_code %}
jQuery(document).ready(function(){
    jQuery('.cloudzoom').CloudZoom({
        tintColor:"{{plugins.cloudzoom.tintColor}}",
        tintOpacity:{{plugins.cloudzoom.tintOpacity}},
        animationTime:{{plugins.cloudzoom.animationTime}},
        galleryEvent:"{{plugins.cloudzoom.galleryEvent}}",
        easeTime:{{plugins.cloudzoom.easeTime}},
        zoomSizeMode:"{{plugins.cloudzoom.zoomSizeMode}}",
        zoomMatchSize:{{plugins.cloudzoom.zoomMatchSize}},
        zoomPosition:{{plugins.cloudzoom.zoomPosition}},
        zoomOffsetX:{{plugins.cloudzoom.zoomOffsetX}},
        zoomOffsetY:{{plugins.cloudzoom.zoomOffsetY}},
        zoomFullSize:{{plugins.cloudzoom.zoomFullSize}},
        uriEscapeMethod:"{{plugins.cloudzoom.uriEscapeMethod}}",
        variableMagnification:{{plugins.cloudzoom.variableMagnification}},
        startMagnification:"{{plugins.cloudzoom.startMagnification}}",
        minMagnification:"{{plugins.cloudzoom.minMagnification}}",
        maxMagnification:"{{plugins.cloudzoom.maxMagnification}}",
        easing:{{plugins.cloudzoom.easing}},
        lazyLoadZoom:{{plugins.cloudzoom.lazyLoadZoom}},
        mouseTriggerEvent:"{{plugins.cloudzoom.mouseTriggerEvent}}",
        disableZoom:{{plugins.cloudzoom.disableZoom | safe}},
        galleryFade:{{plugins.cloudzoom.galleryFade}},
        galleryHoverDelay:{{plugins.cloudzoom.galleryHoverDelay}},
        permaZoom:{{plugins.cloudzoom.permaZoom}},
        zoomWidth:{{plugins.cloudzoom.zoomWidth}},
        zoomHeight:{{plugins.cloudzoom.zoomHeight}},
        lensWidth:{{plugins.cloudzoom.lensWidth}},
        lensHeight:{{plugins.cloudzoom.lensHeight}},
        hoverIntentDelay:{{plugins.cloudzoom.hoverIntentDelay}},
        hoverIntentDistance:{{plugins.cloudzoom.hoverIntentDistance}},
        autoInside:{{plugins.cloudzoom.autoInside}},
        disableOnScreenWidth:{{plugins.cloudzoom.disableOnScreenWidth}},
        touchStartDelay:{{plugins.cloudzoom.touchStartDelay}},
        appendSelector:"{{plugins.cloudzoom.appendSelector}}",
        propagateGalleryEvent:{{plugins.cloudzoom.propagateGalleryEvent}}
    });
})
{%endblock%}

{% block main %}
<img class = "cloudzoom" src = "{{conf.main_url_root}}/files/{{thumb}}" title="{{thumb_title}}"
        data-cloudzoom = "zoomImage: '{{conf.main_url_root}}/files/{{image_thumb}}'" />


        {% endblock %}
EOD;
