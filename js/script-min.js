//CSS
var stylesheet = document.createElement('link');
stylesheet.href = '/css/top.'+cacheBuster+'.css';
stylesheet.rel = 'stylesheet';
stylesheet.type = 'text/css';
stylesheet.media = 'all';
document.getElementsByTagName('head')[0].appendChild(stylesheet);

dom.listing = document.getElementById("listing");
dom.tracks = document.querySelectorAll(".show-track");
dom.selectedArtwork = document.getElementById("selected-artwork");
dom.selectedTrack = document.getElementById("selected-track-link");
dom.selectedLink = document.getElementById("selected-link");
dom.footer = document.getElementById("footer");
dom.title = document.getElementById("title");
dom.graph = document.getElementById("graph");
dom.close = document.getElementById("close");



global.createCookie = function(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
};

global.readCookie = function(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)===' '){ c = c.substring(1,c.length);}
        if (c.indexOf(nameEQ) === 0){ return c.substring(nameEQ.length,c.length);}
    }
    return null;
};

// var eraseCookie = function(name) {
//     createCookie(name,"",-1);
// };


global.updateUrls = function(code){

	global.createCookie("country",code, 30);
    if(code !== "us" && typeof(Storage) !== "undefined"){
        var links = dom.listing.querySelectorAll("a.preview");
        for (var i = links.length - 1; i >= 0; i--) {
            var id = links[i].getAttribute("data-target-id");
            if(url = localStorage.getItem(id+"-"+code)){
                links[i].href= url;
            }else{
                var term = encodeURIComponent(links[i].getAttribute("data-artist") + " " + links[i].getAttribute("data-album") + " " + links[i].getAttribute("data-track"));
                global.loadScript('http://itunes.apple.com/search?term='+ term +'&media=music&entity=song&limit=1&callback=processTrack&country='+ code); 
            }            
        }
    }
   	
};

function processTrack (response) {
    if(response.resultCount == 1){
        var track = response.results[0];
        if(track.country !== "USA"){
            var url = track.trackViewUrl;
            var uniqueId = (track.trackName + " " + track.artistName).toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/\s+/gi,'-');
          //  console.log(uniqueId);
            try{
               localStorage.setItem(uniqueId+"-"+country, url);
            }catch(e){
                console.error("Localstorage error: " + e);
                localStorage.clear();
            }
            
            if(element = document.getElementById("link-"+uniqueId)){
                element.href=url;
            }else{
                console.error(uniqueId + " track doesn't match");
            }
        }
    }
}
function processGeolocation(response){
	if(response){
        country = response.country.toLowerCase();
		global.updateUrls(country);					
	}
}
global.easeInOut = function(currentTime, start, change, duration) {
    currentTime /= duration / 2;
    if (currentTime < 1) {
        return change / 2 * currentTime * currentTime + start;
    }
    currentTime -= 1;
    return -change / 2 * (currentTime * (currentTime - 2) - 1) + start;
};

global.scrollTo = function(element, to, duration) {
    var start = element.scrollTop,
        change = to - start,
        increment = 20;

    var animateScroll = function(elapsedTime) {        
        elapsedTime += increment;
        var position = global.easeInOut(elapsedTime, start, change, duration);                        
        element.scrollTop = position; 
        if (elapsedTime < duration) {
            setTimeout(function() {
                animateScroll(elapsedTime);
            }, increment);
        }
    };

    animateScroll(0);
};


global.selectTrack = function(target){
    var src = target.getAttribute("data-src");
    dom.selectedArtwork.src = src;
    dom.selectedArtwork.className= "";
    target.className += " selected";
   dom.selectedArtwork.alt =  dom.selectedTrack.title = dom.selectedLink.title =  dom.selectedTrack.textContent = target.title;

    dom.selectedTrack.href = dom.selectedLink.href= target.getElementsByTagName("a")[0].href;

    var newWidth  = artworkSizes[artworkSizes.length-1];
    for (var i = 0; i < artworkSizes.length; i++) {
        if(dom.selectedArtwork.offsetWidth <= artworkSizes[i]){
            newWidth = artworkSizes[i];
            break;
        }
    }     
    
    var tmp = new Image();
    tmp.onload=function(){
        dom.selectedArtwork.src = tmp.src;
        dom.selectedArtwork.className = "loaded";
    };
    tmp.src = src.replace("100x100", newWidth+"x"+newWidth);

    if(dom.listing.scrollTop){
        global.scrollTo(listing, 0, 500);
    }
};


global.clickTrack = function(e) {
        
        if(this.tagName !== "TR"){
            e.preventDefault();
        }
        dom.body.className="listing-open";
        //alert(this.getAttribute("data-target-id"));
        var selected = dom.listing.querySelector("tr.selected");
        if(selected){
            selected.className = selected.className.replace(" selected", "");
        }
        var _id = this.getAttribute("data-target-id");
        window.location.hash = _id;
        var target = document.getElementById("track-" + _id);
        if(target){
           global.selectTrack(target);
        }
};
if(window.location.hash.length > 1){
   var target = document.getElementById("track-" + window.location.hash.substring(1));  
   //alert(window.location.hash.substring(1));
    if(target){
        dom.body.className="listing-open";
        global.selectTrack(target);
    }
}
if(dom.close){
    dom.close.addEventListener("click", function(e){
        e.preventDefault();
        history.pushState({}, '', "#");
        dom.body.className="";
    }, false);
}
if(dom.listing){
   for (var i = dom.tracks.length - 1; i >= 0; i--) {
        dom.tracks[i].addEventListener('click', global.clickTrack, true);
    } 
    var country = global.readCookie("country");
    if(country){
        global.updateUrls(country);
    }else{
        global.loadScript("http://ipinfo.io/?callback=processGeolocation"); 
    }
}
/*
{
artistName: "The Weeknd"
artworkUrl100: "http://is3.mzstatic.com/image/thumb/Music1/v4/a4/b8/10/a4b8104b-c210-b7e8-d89d-84a418638b83/UMG_cvrart_00602547490759_01_RGB72_1800x1800_15UMGIM36513.jpg/100x100bb-85.jpg"
collectionId: "1017804831"
collectionName: "Beauty Behind the Madness"
firstPlay: "1440864001"
lastPlay: "1441164842"
numberOfTracks: "8"
primaryGenreName: "R&B/Soul"
releaseDate: "1440709200"
totalPlays: "97"
}
*/

var svg = svg || {};

global.drawD3 = function(){
    //console.log("Drawing up D3");
   
    svg.w = dom.graph.offsetWidth -  (svg.pad*2);
    svg.h = dom.graph.offsetHeight - dom.title.offsetHeight - dom.footer.offsetHeight - (svg.pad*2);
    svg.radius =  Math.max(Math.min(Math.min(svg.w, svg.h)/10, 50), 25);
    //console.log(svg.radius);

    svg.xDomain = [ d3.min(topAlbums, function (d) { return +d[svg.xKey]; })  ,
                    d3.max(topAlbums, function (d) { return +d[svg.xKey]; }) ];  

    svg.tDomain = [ d3.max(topAlbums, function (d) { return +d[svg.tKey]; })  ,
                    d3.min(topAlbums, function (d) { return +d[svg.tKey]; }) ];  

    svg.yDomain = [ d3.min(topAlbums, function (d) { return +d[svg.yKey]; })  ,
                    d3.max(topAlbums, function (d) { return +d[svg.yKey]; }) ];

    svg.rDomain = [ d3.min(topAlbums, function (d) { return +d[svg.rKey]; }) , 
                    d3.max(topAlbums, function (d) { return +d[svg.rKey]; })];

    svg.x = d3.time.scale().domain(svg.xDomain).range([svg.radius+svg.pad*2, svg.w-svg.radius]),
    svg.y = d3.scale.linear().domain(svg.yDomain).range([ svg.h-svg.radius-svg.pad*2,svg.radius]),
    svg.r = d3.scale.linear().domain(svg.rDomain).range([ svg.radius/3,svg.radius]);
    svg.t = d3.scale.linear().domain(svg.tDomain).range([ 0, 10000]);


    svg.xAxis.scale(svg.x);/*.ticks(6)
        .tickFormat(function (d, i) {
            return ['', 'few plays','', 'quite a bit', '', 'a lot'][i];
        });*/
    svg.yAxis.scale(svg.y);


    svg.canvas.attr("width", svg.w).attr("height", svg.h);

    svg.xAxisGroup.attr("transform", "translate("+0+", "+(svg.h - 6)+")").call(svg.xAxis);
    svg.yAxisGroup.call(svg.yAxis);
    svg.legend.selectAll("text").attr("x", svg.w -svg.radius - 24).attr("y", svg.radius);
    svg.legend.selectAll("circle").attr("cx", svg.w -  svg.radius - 9).attr("cy", svg.radius);


    svg.xLabel.attr("x", svg.w - svg.radius).attr("y", svg.h - svg.pad);
    svg.yLabel.attr("x", - svg.radius);

    svg.circles.attr("cx", function (d) { return svg.x(+d[svg.xKey]); })
                .attr("cy", function (d) { return svg.y(+d[svg.yKey]); })
                .attr("r", 5)
                .style("stroke-width", Math.floor(svg.radius/10))
                .style("fill-opacity", 1e-6)
                .style("stroke-opacity", 1e-6)
                .transition()
                .duration(500)
                .delay(function (d) { return svg.t(+d[svg.tKey]); })
                .attr("r", function (d) { return svg.r(+d[svg.rKey]); })
                .style("fill-opacity", 1)
                .style("stroke-opacity", 1);
    
   
};


global.setupD3 = function(container){

    d3.selection.prototype.moveToFront = function() {
        //console.log("front");
        return this.each(function(){
            this.parentNode.appendChild(this);
        });
    };
    d3.selection.prototype.moveToBack = function() {
        // console.log("back");

        return this.each(function() { 
            var firstChild = this.parentNode.firstChild; 
            if (firstChild) { 
                this.parentNode.insertBefore(this, firstChild); 
            } 
        }); 
    };
   // console.log("Setting up D3");
    svg.pad = dom.title.offsetTop;
    svg.yKey = "totalPlays";
    svg.xKey = "firstPlay";
    svg.rKey = "numberOfTracks";
    svg.cKey = "primaryGenreName";
    svg.tKey = "lastPlay";

    svg.canvas = d3.select("#graph").append("svg");
    
   
    // setup fill color
    svg.cValue = function(d) { return d[svg.cKey];},
    svg.color = d3.scale.category20();

    svg.xAxisGroup = svg.canvas.append("g")
    .attr("class", "axis");
 
    svg.yAxisGroup = svg.canvas.append("g")
        .attr("class", "axis")
        .attr("transform", "translate("+5+", 0)");


    svg.xAxis = d3.svg.axis().orient("bottom").tickFormat(function (d) { return ''; });
    svg.yAxis = d3.svg.axis().orient("left").tickFormat(function (d) { return ''; });
        
 
    svg.canvas.append("defs").selectAll("pattern").data(topAlbums)
        .enter()
        .append('pattern')
        .attr('id', function(d) { return d["collectionId"];})
        .attr('x', 0)
        .attr('y', 0)
        .attr('width', "100%")
        .attr('height', "100%")
        .attr('viewBox', "0 0 100 100")
        .append('image')
        .attr('xlink:href', function(d) { return d["artworkUrl100"];})
        .attr('x', 0)
        .attr('y', 0)
        .attr('width', 100)
        .attr('height', 100)    ;

    svg.circles = svg.canvas.selectAll("circle")
        .data(topAlbums)
        .enter()
        .append("circle");

    svg.circles.on("mouseover",function(){      
        //d3.select(this).style("transform","scale(2)");
        d3.select(this).moveToFront().transition()
                .duration(500).style("stroke-width", 1).attr("r", function (d) { return svg.radius; });
    });
    svg.circles.on("mouseout",function(){      
        var cur = d3.select(this);
        cur.transition().duration(250).style("stroke-width", Math.floor(svg.radius/10)).attr("r", function (d) { return svg.r(+d[svg.rKey]); });
        setTimeout(function(){cur.moveToBack();}, 251);
      //d3.select(this).style("transform","scale(1)");
    });



    svg.circles.attr("class", "circle")
        .style("fill", function(d) { return "url(#" + d["collectionId"] + ")";})
        .style("stroke", function(d) { return svg.color(svg.cValue(d));})
        .append("svg:title")
          .text(function(d) { return d["collectionName"] + ' — ' + d["artistName"]; });     // displays small black dot
        

    svg.legend = svg.canvas.selectAll(".legend")
        .data(svg.color.domain())
        .enter().append("g")
        .attr("class", "legend")
        .attr("transform", function(d, i) { return "translate(0," + i * 24 + ")"; });

    // draw legend colored rectangles
    svg.legend.append("circle")
      .attr("r", 9)
      .style("fill", svg.color);

    // draw legend text
    svg.legend.append("text")
      .attr("dy", ".35em")
      .style("text-anchor", "end")
      .text(function(d) { return d;});

    svg.xLabel = svg.canvas.append("g")
                .attr("class", "label")
                .append("text")
                .attr("dy", ".35em")
                .style("text-anchor", "end")
                .text("release date");
    svg.yLabel = svg.canvas.append("g")
                .attr("class", "label")
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("dy", ".35em")
                .style("text-anchor", "end")
                .attr("y", svg.pad)
                .text("amount of plays");
    global.drawD3();
    window.addEventListener("resize",  global.drawD3);
};

if(dom.graph){
   // console.log("Loading D3");
    global.loadScript("http://d3js.org/d3.v3.min.js", global.setupD3);

}

