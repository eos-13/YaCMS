<?php
{% extends "base.html" %}


{% block main %}
<p>{{main | safe}}</p>
{{plugins.wowslider.HTML|safe}}

{% endblock %}