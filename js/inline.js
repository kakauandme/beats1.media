var body = document.getElementsByTagName("body")[0]; body.className = ""; //remove no-js
var grid = document.getElementById("grid");
var top100grid = document.getElementById("top100grid");

var artworkSizes = [200,400,600,1200,1500];

var layout =  function(g){
	var msnry = new Masonry( g, {
			itemSelector: 'div.grid-item',
			//columnWidth: '.grid-sizer',
			percentPosition: true,
			transitionDuration: 0
	 });
};
var updateImage = function(item){
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
var t = document.getElementsByTagName('script')[0];
var loadScript = function(src, callback, arg){
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
	t.parentNode.insertBefore(s, t);
	s.src = src; 
};
if(grid){

	loadScript("https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js", layout, grid); 

	var items = grid.children;
	for(var i = 0; i < items.length; i++) {
		updateImage(items[i]);
	};
}