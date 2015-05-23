<?php


$model = <<<EOD

{% extends "base.html" %}

{{ block.super }}
{% block main %}
<p>{{main | safe}}</p>
<center>
<div id="circleslider">
    <div class="viewport">
        <ul class="overview">
            {% for i in image_path %}
            <li><a href="{{conf.main_url_root}}/files/{{i.path}}" rel="group"><img style="max-width: {{plugins.circularslider.imgmaxwidth}}px; height: auto; min-height: {{plugins.circularslider.imgminheight}}px;" src="{{conf.main_url_root}}/files/{{i.path}}" /></a></li>
            {% endfor %}
        </ul>
    </div>
    <div class="dot"></div>
    <div class="overlay"></div>
    <div class="thumb"></div>
</div>
</center>
<script>
jQuery(document).ready(function()
{
    jQuery("#circleslider").tinycircleslider({
        interval: {{plugins.circularslider.interval}},
        intervalTime: {{plugins.circularslider.intervalTime}},
        dotsSnap: {{plugins.circularslider.dotsSnap}},
        dotsHide: {{plugins.circularslider.dotsHide}},
        radius: {{plugins.circularslider.radius}},
        start: {{plugins.circularslider.start}}
    });
    {% if plugins.circularslider.use_fancy_box %}
        jQuery("#circleslider").find("a").fancybox();
    {%endif%}
});
</script>
{% endblock %}

EOD;

