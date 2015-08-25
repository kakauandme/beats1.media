//CSS
var stylesheet = document.createElement('link');
stylesheet.href = '/css/top.css';
stylesheet.rel = 'stylesheet';
stylesheet.type = 'text/css';
stylesheet.media = 'all';
document.getElementsByTagName('head')[0].appendChild(stylesheet);

var createCookie = function(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
};

var readCookie = function(name) {
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


var updateUrls = function(code){
	createCookie("country",code, 7);
	//TODO: not all links work, ajax call to iTunes API might help
	// for(var i = 0; i < items.length; i++) {
	// 	var a = items[i].getElementsByTagName("a")[0];
	// 	a.href = a.href.replace("/us/","/"+code+"/");
	// };		
};
function processGeolocation(response){
	if(response){
		updateUrls(response.country.toLowerCase());					
	}
}
function easeInOut(currentTime, start, change, duration) {
    currentTime /= duration / 2;
    if (currentTime < 1) {
        return change / 2 * currentTime * currentTime + start;
    }
    currentTime -= 1;
    return -change / 2 * (currentTime * (currentTime - 2) - 1) + start;
}

function scrollTo(element, to, duration) {
    var start = element.scrollTop,
        change = to - start,
        increment = 20;

    var animateScroll = function(elapsedTime) {        
        elapsedTime += increment;
        var position = easeInOut(elapsedTime, start, change, duration);                        
        element.scrollTop = position; 
        if (elapsedTime < duration) {
            setTimeout(function() {
                animateScroll(elapsedTime);
            }, increment);
        }
    };

    animateScroll(0);
}



var listing = document.getElementById("listing");
var tracks = document.querySelectorAll(".show-track");
var selectedArtwork = document.getElementById("selected-artwork");
var selectedTrack = document.getElementById("selected-track");
var selectedLink = document.getElementById("selected-link");

var selectTrack = function(target){
    var src = target.getAttribute("data-src");
    selectedArtwork.src = src;
    selectedArtwork.className= "";
    target.className += " selected";
    selectedArtwork.alt =  selectedTrack.title = selectedLink.title =  selectedTrack.textContent = target.title;

    selectedTrack.href = selectedLink.href= target.getElementsByTagName("a")[0].href;

    var newWidth  = artworkSizes[artworkSizes.length-1];
    for (var i = 0; i < artworkSizes.length; i++) {
        if(selectedArtwork.offsetWidth <= artworkSizes[i]){
            newWidth = artworkSizes[i];
            break;
        }
    }     
    
    var tmp = new Image();
    tmp.onload=function(){
        selectedArtwork.src = tmp.src;
        selectedArtwork.className = "loaded";
    };
    tmp.src = src.replace("100x100", newWidth+"x"+newWidth);

    if(listing.scrollTop){
        scrollTo(listing, 0, 500);
    }
};


var clickTrack = function(e) {
        
        if(this.tagName !== "TR"){
            e.preventDefault();
        }
        body.className="listing-open";
        //alert(this.getAttribute("data-target-id"));
        var selected = listing.querySelector("tr.selected");
        if(selected){
            selected.className = selected.className.replace(" selected", "");
        }
        var _id = this.getAttribute("data-target-id");
        window.location.hash = _id;
        var target = document.getElementById("track-" + _id);
        if(target){
           selectTrack(target);
        }
};
if(window.location.hash.length > 1){
   var target = document.getElementById("track-" + window.location.hash.substring(1));  
   //alert(window.location.hash.substring(1));
    if(target){
        body.className="listing-open";
        selectTrack(target);
    }
}
document.getElementById("close").addEventListener("click", function(){
    body.className="";
}, false);

for (var i = tracks.length - 1; i >= 0; i--) {
    tracks[i].addEventListener('click', clickTrack, true);
}


var country = readCookie("country");
if(country){
    updateUrls(country);
}else{
    loadScript("http://ipinfo.io/?callback=processGeolocation"); 
}