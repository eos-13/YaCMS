<?php

$model = <<<EOD

{% extends "base.html" %}


{% block main %}

        <script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">
            <div class="rg-image-wrapper">

                    <div class="rg-image-nav">
                        <a href="#" class="rg-image-nav-prev">Previous Image</a>
                        <a href="#" class="rg-image-nav-next">Next Image</a>
                    </div>

                <div class="rg-image"></div>
                <div class="rg-loading"></div>
                <div class="rg-caption-wrapper">
                    <div class="rg-caption" style="display:none;">
                        <p></p>
                    </div>
                </div>
            </div>
        </script>

{{ block.super |safe }}

<div id="rg-gallery" class="rg-gallery">
    <div class="rg-thumbs">
        <div class="es-carousel-wrapper">
            <div class="es-nav">
                <span class="es-nav-prev">Previous</span>
                <span class="es-nav-next">Next</span>
            </div>
        <div class="es-carousel">
            <ul>
                {% for i in thumb_image_path %}
                    <li>
                        <a href="#">
                            <img
                                src="{{conf.main_url_root}}/files/{{i.thb_path}}"
                                data-large="{{conf.main_url_root}}/files/{{i.path}}"
                                 data-description="{% if i.data-description %} {{i.data-description}} {%endif%}"
                                />
                        </a>
                    </li>
                {% endfor %}
            </ul>
        </div>
    </div>
</div>
{%endblock%}

EOD;

