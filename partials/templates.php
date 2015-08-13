<?php
$templates = array(

'now' => '<div id="now" style="background-image: url({{artworkUrl}})"><h1>{{artistName}} &mdash; {{trackName}}</h1>',
'track' => '<div class="grid-item{{className}}" data-id="{{trackId}}" data-src= "{{artworkUrl100}}" data-plays="{{plays}}"><a class="preview" target="_blank" href=" {{trackViewUrl}} " title="{{title}}{{trackName}}  &mdash; {{artistName}}"><img class="artwork" src= "{{artworkUrl100}}"  alt="{{trackName}} &mdash; {{artistName}}" />{{# className }}<div class="badge"></div>{{/ className }}</a></div>'

);


