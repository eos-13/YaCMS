<?php

$model = <<<EOD

{% extends "base.html" %}

{{ block.super }}
{%block plugin_js_code %}
jQuery(document).ready(function(){
    jQuery("#slider").Thumbelina({
        orientation:"{{plugins.thumbnelia.orientation}}",
        easing:{{plugins.thumbnelia.easing}},
        maxSpeed:{{plugins.thumbnelia.maxSpeed}},
        {%if plugins.thumbnelia.bwdBut == "null" %}
    $bwdBut:null,
        {% else %}
    $bwdBut:{{plugins.thumbnelia.bwdBut | safe}},
        {%endif%}
        {%if plugins.thumbnelia.fwdBut == "null" %}
    $bwdBut:null,
        {% else %}
        $fwdBut:{{plugins.thumbnelia.fwdBut | safe}},
        {%endif%}
    });
    {% if plugins.thumbnelia.use_fancy_box %}
        jQuery("#slider").find("a").fancybox();
    {% endif %}

});
{%endblock%}


{% block main %}
<p>{{main | safe}}</p>

<div id="sliderwrapper">
<div  id="slider" class="{{plugins.thumbnelia.orientation}}">
            <div class="thumbelina-but rwd {%if plugins.thumbnelia.orientation == "vertical" %} vert  top  {% else  %} horiz  left {% endif %} ">{%if plugins.thumbnelia.orientation == "vertical" %}&#708;{%else%}&#706{%endif%}</div>
            <ul>
{% for i in thumb_image_path %}
                    <li><a href="{{conf.main_url_root}}/files/{{i.path}}">
                              <img {%if plugins.thumbnelia.imagewidth != "0" %}
                                       width="{{plugins.thumbnelia.imagewidth}}"
                                       {%endif %}
                                       {%if plugins.thumbnelia.imageheight != "0" %}
                                       height="{{plugins.thumbnelia.imageheight}}"
                                       {%endif %}
                                       src="{{conf.main_url_root}}/files/{{i.thb_path}}"
                                       title="{{i.title}}">
                           </a>
                    </li>
                {% endfor %}

            </ul>
            <div class="thumbelina-but fwd {%if plugins.thumbnelia.orientation == "vertical" %} vert  bottom {% else %} horiz  right {% endif %}">{%if plugins.thumbnelia.orientation == "vertical" %}&#709;{%else%}&#707{%endif%}</div>
        </div>
</div>
</div>
{% endblock %}

EOD;
