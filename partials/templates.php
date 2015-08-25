<?php
$templates = array(

'now' => '<div id="now" style="background-image: url({{artworkUrl}})"><h1>{{artistName}} &mdash; {{trackName}}</h1>',
'topgrid' => '<div id="grid"><!--
				{{# tracks }}
					--><div class="show-track grid-item" data-id="{{trackId}}" data-src="{{artworkUrl100}}" data-plays="{{plays}}" data-target-id="{{unique_tile}}">
						<a class="preview" target="_blank" href=" {{trackViewUrl}} " title="{{trackName}}  &mdash; {{artistName}}" >
							<img class="artwork" src= "{{artworkUrl100}}"  alt="{{trackName}} &mdash; {{artistName}}" />
						</a>
					</div><!--
				{{/ tracks }}
			--></div>',
'toplisting' => '<div id="listing">
					<a href="#" id="close" title="Hide">▼</a>
					<span class="column details">
						<a id="selected-link" href="#"><img id="selected-artwork" src="" width="100%"/></a>
						<p class="selected-track"><a id="selected-track" href="#">Hello - world</a></p>
					</span><span class="column list">
						
						<table>
							<tr class="heading"><td colspan="5"><h1>Beats 1 Top Tracks</h1></td></tr>
							{{# tracks }}
								<tr id="track-{{unique_tile}}" class="list-item show-track" data-id="{{trackId}}" data-src= "{{artworkUrl100}}" data-plays="{{plays}}" data-target-id="{{unique_tile}}"  title="{{trackName}}  &mdash; {{artistName}}">
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
										<p><a class="preview" target="_blank" href="{{trackViewUrl}}" title="Open in Apple Music">▶</a></p>
									</td>
								</tr>
							{{/ tracks }}
						</table>
					</span>
				</div>'

);


