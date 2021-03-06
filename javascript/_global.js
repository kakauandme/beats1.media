var dom = dom || {};
var global = global || {};


dom.body = document.getElementsByTagName("body")[0]; dom.body.className = ""; //remove no-js

dom.script = document.getElementsByTagName('script')[0];
global.artworkSizes = [200,400,600,1200,1500];

global.loadScript = function(src, callback, arg){
	var r = false;
	var s = document.createElement('script');
	s.type = 'text/javascript';  
	s.async = "async";
	s.onload = s.onreadystatechange = function() {
		//console.log( this.readyState ); //uncomment this line to see which ready states are called.
		if ( !r && (!this.readyState || this.readyState === 'complete') )
		{
			r = true;
			if(callback){
				callback(arg);
			}					
		}
	};	
	dom.script.parentNode.insertBefore(s, dom.script);
	s.src = src; 
};

global.getImageWidth = function(w){
	var newWidth  = global.artworkSizes[global.artworkSizes.length-1];
	for (var i = 0; i < global.artworkSizes.length; i++) {
		if(w <= global.artworkSizes[i]){
			newWidth = global.artworkSizes[i];
			break;
		}
	}

	return newWidth;	
};

//CSS
var stylesheet = document.createElement('link');
stylesheet.href = '/css/style.'+cacheBuster+'.css';
stylesheet.rel = 'stylesheet';
stylesheet.type = 'text/css';
stylesheet.media = 'all';
document.getElementsByTagName('head')[0].appendChild(stylesheet);