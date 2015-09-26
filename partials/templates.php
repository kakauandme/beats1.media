<?php
$templates = array(

'now' => '<h1><a id="link-{{unique_title}}" class="preview" target="_blank" data-artist="{{artistName}}" data-track="{{trackName}}" data-album="{{collectionName}}" href="{{trackViewUrl}}" data-target-id="{{unique_title}}" title="Open in Apple Music">{{artistName}} &mdash; {{trackName}}</a></h1>
		<div id="cover" style="background-image: url({{artworkUrl}})" title="Play">
		<span id="play">play</span>
			 <audio id="audio" src="{{previewUrl}}" preload="auto" loop volume="0.5">
				<p>Your browser does not support the <code>audio</code> element </p>
			</audio>
		</div>',
'topgrid' => '<div id="{{id}}"><!--
				{{# tracks }}
					--><div class="show-track grid-item" data-id="{{trackId}}" data-src="{{artworkUrl100}}" data-plays="{{plays}}" data-target-id="{{unique_title}}">
						<a id="album-{{unique_title}}" class="preview" target="_blank" href=" {{trackViewUrl}} " title="{{trackName}} &mdash; {{artistName}}" >
							<img class="artwork" src= "{{artworkUrl100}}"  alt="{{trackName}} &mdash; {{artistName}}" />
						</a>
					</div><!--
				{{/ tracks }}
			--></div>',
'top100grid' => '<div id="{{id}}"><!--
				{{# tracks }}
					--><div class="grid-item" data-id="{{trackId}}"">
						<a  class="preview" target="_blank" href=" {{trackViewUrl}} " title="{{collectionName}} &mdash; {{artistName}}" >
							<img class="artwork" src= "{{artworkUrl100}}"  alt="{{collectionName}} &mdash; {{artistName}}" />
						</a>
					</div><!--
				{{/ tracks }}
			--></div>',
'toplisting' => '<div id="listing">
					<a href="#" id="close" title="Hide">▼</a>
					<span class="column details">
						<a target="_blank" id="selected-link" href="#" title="Open in Apple Music"><img id="selected-artwork" src="" width="100%"/></a>
						<p id="selected-track"><a target="_blank" id="selected-track-link" href="#" title="Open in Apple Music"></a></p>
					</span><span class="column list">
						
						<table>
							<tr class="heading"><td colspan="5"><h1>'.$title.'</h1></td></tr>
							{{# tracks }}
								<tr id="track-{{unique_title}}" class="list-item show-track" data-id="{{trackId}}" data-src= "{{artworkUrl100}}" data-plays="{{plays}}" data-target-id="{{unique_title}}"  title="{{trackName}}  &mdash; {{artistName}}">
									<td class="index"><p>{{i}}.</p></td>
									<td class="track">									
											<p>{{trackName}}</p>
									</td>
									<td class="artist">
											<p>{{artistName}}</p>
									</td>
									<td class="genre">
											<p>{{primaryGenreName}}</p>
									</td>
									<td class="link">
										<p><a id="link-{{unique_title}}" class="preview" target="_blank" data-artist="{{artistName}}" data-track="{{trackName}}" data-album="{{collectionName}}" href="{{trackViewUrl}}" data-target-id="{{unique_title}}" title="Open in Apple Music">♫</a></p>
									</td>
								</tr>
							{{/ tracks }}
						</table>
					</span>
				</div>',
'topgraph' => '<div id="graph">
				</div>'

);


