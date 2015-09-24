var dom = dom || {};


var global = global || {};

dom.body = document.getElementsByTagName("body")[0]; dom.body.className = ""; //remove no-js
dom.grid = document.getElementById("grid");
dom.top100grid = document.getElementById("top100grid");
dom.script = document.getElementsByTagName('script')[0];

var artworkSizes = [200,400,600,1200,1500];

global.layout =  function(g){
	var msnry = new Masonry( g, {
			itemSelector: 'div.grid-item',
			//columnWidth: '.grid-sizer',
			percentPosition: true,
			transitionDuration: 0
	 });
};
global.updateImage = function(item){
	var width = item.offsetWidth;
	var img = item.getElementsByTagName("img")[0];

	if(img.complete) {
			item.className +=" complete";
	}else{
 		img.onload=function(){
 			if((' ' + item.className + ' ').indexOf(' complete ') === -1){
 				item.className +=" complete";
 			}
  			
	  	};
	}
	if(width > 100){
			
	  	var src = item.getAttribute("data-src");
	  	
	  	if(src && width){		  		
	  		item.className+=" loading";		
	  		var newWidth  = artworkSizes[artworkSizes.length-1];
	  		for (var i = 0; i < artworkSizes.length; i++) {
	  			if(width <= artworkSizes[i]){
	  				newWidth = artworkSizes[i];
	  				break;
	  			}
	  		}; 		
     		
		  	var tmp = new Image();
		  	tmp.onload=function(){
		  		img.src = tmp.src;
	  			item.className = item.className.replace(" loading","");
		  	};
		  	tmp.onerror=function(){
     			item.className = item.className.replace(" loading","");
		  	};
	  		tmp.src = src.replace("100x100", newWidth+"x"+newWidth);
	  	}

	}else{//small image
	
		img.onerror=function(){
	  		item.parentElement.removeChild(item);
	  	};
	}
};		

global.loadScript = function(src, callback, arg){
	var r = false;
	var s = document.createElement('script');
	s.type = 'text/javascript';  
	s.async = "async";
	s.onload = s.onreadystatechange = function() {
		//console.log( this.readyState ); //uncomment this line to see which ready states are called.
		if ( !r && (!this.readyState || this.readyState == 'complete') )
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
if(dom.grid){

	global.loadScript("https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js", global.layout, dom.grid); 

	var items = dom.grid.children;
	for(var i = 0; i < items.length; i++) {
		global.updateImage(items[i]);
	};
}