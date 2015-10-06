// @codekit-prepend "_svg.js"

dom.listing = document.getElementById("listing");
dom.tracks = document.querySelectorAll(".show-track");
dom.selectedArtwork = document.getElementById("selected-artwork");
dom.selectedTrack = document.getElementById("selected-track-link");
dom.selectedLink = document.getElementById("selected-link");
dom.footer = document.getElementById("footer");
dom.title = document.getElementById("title");
dom.graph = document.getElementById("graph");
dom.close = document.getElementById("close");
dom.now = document.getElementById("now");
dom.cover = document.getElementById("cover");
dom.play = document.getElementById("play");
dom.audio = document.getElementById("audio");

dom.links = document.getElementById("links").children;
global.navClick = function(e){
    // ga('send', 'pageview', {
    //     'page': e.target.href,
    //     'title': e.target.title
    // });
    ga('send', 'event', 'Interface', 'Navigate'); 
    e.preventDefault();
    window.location.href = e.target.href;
};



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
        var items = [];
        if(dom.listing){
            items = dom.listing.querySelectorAll("a.preview");
        }else if(dom.now){
            items = dom.now.querySelectorAll("a.preview");
        }
        for (var i = 0; i < items.length; i++) {         
            var id = items[i].getAttribute("data-target-id");
            if(url = localStorage.getItem(id+"-"+code)){
                items[i].href= url;
            }else{
                var term = encodeURIComponent(items[i].getAttribute("data-artist") + " " + items[i].getAttribute("data-album") + " " + items[i].getAttribute("data-track"));
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
               localStorage.setItem(uniqueId+"-"+global.country, url);
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
};
function processGeolocation(response){
	if(response){
        global.country = response.country.toLowerCase();
		global.updateUrls(global.country);					
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
   dom.selectedArtwork.alt =  dom.selectedTrack.textContent = target.title;

    dom.selectedTrack.href = dom.selectedLink.href= target.getElementsByTagName("a")[0].href;

    var newWidth  = global.getImageWidth(dom.selectedArtwork.offsetWidth);
    
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
    var target = document.getElementById("track-" + _id);
    if(target){
       global.selectTrack(target);
       history.pushState({}, '', "#" +_id);
    }
    ga('send', 'event', 'Track', 'Zoom');
};


if(window.location.hash.length > 2){
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
        dom.body.className="";
        history.pushState({}, '', "#");
    }, false);
}


if(dom.listing){
    for (var i = 0; i < dom.tracks.length; i++) {
       
        dom.tracks[i].addEventListener('click', global.clickTrack, true);
    } 
    
}


if(dom.graph){
   // console.log("Loading D3");
    global.loadScript("http://d3js.org/d3.v3.min.js", svg.setupD3);

}

global.playPause = function(){
    if(dom.cover.className.length){
       dom.cover.className = ""; 
       dom.play.textContent = "play";
       dom.cover.title="Play";
       dom.audio.pause();
    }else{
        ga('send', 'event', 'Track', 'Play');
       dom.cover.className= "playing" ;
       dom.play.textContent = "pause";
       dom.cover.title="Pause";
       dom.audio.play();
    }
};
if(dom.cover){
    dom.cover.addEventListener('click', global.playPause, true);
}

for (var i = 0; i < dom.links.length; i++) {
    dom.links[i].addEventListener("click", global.navClick, true);
};

global.country = global.readCookie("country");
if(global.country){
    global.updateUrls(global.country);
}else{
    global.loadScript("http://ipinfo.io/?callback=processGeolocation"); 
}