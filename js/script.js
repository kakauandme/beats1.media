//CSS
var stylesheet = document.createElement('link');
stylesheet.href = '/css/top.css';
stylesheet.rel = 'stylesheet';
stylesheet.type = 'text/css';
stylesheet.media = 'all';
document.getElementsByTagName('head')[0].appendChild(stylesheet);

var createCookie = function(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

var readCookie = function(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
};

var eraseCookie = function(name) {
    createCookie(name,"",-1);
};


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
var country = readCookie("country");
		if(country){
	updateUrls(country);
}else{
	loadScript("http://ipinfo.io/?callback=processGeolocation"); 
}