<?php

$model = <<<EOD

{% extends "standard.html" %}

{%block plugin_js_code %}

jQuery(document).ready(function()
{
    jQuery("a.click").simpletooltip(
    {
            click : {{plugins.tooltips.click}},
            hideDelay : {{plugins.tooltips.delay}},
            showEffect : "{{plugins.tooltips.showEffect}}",
            hideEffect : "{{plugins.tooltips.hideEffect}}"
    });
});
{% endblock %}



{% block subpage %}

{{ block.super }}
{% for k,i in plugins.tooltips.tooltips %}
<div id="{{i.title}}" class="tooltip big els" style="display: none;">
{{i.content|safe}}
</div>
{% endfor %}
{% endblock %}

EOD;
