var body=document.getElementById("top");body.className="";var grid=document.getElementById("grid"),artworkSizes=[200,400,600,1200,1500],layout=function(e){var a=new Masonry(e,{itemSelector:"div.grid-item",percentPosition:!0,transitionDuration:0})},updateImage=function(e){var a=e.offsetWidth,t=e.getElementsByTagName("img")[0];if(t.complete?e.className+=" complete":t.onload=function(){-1===(" "+e.className+" ").indexOf(" complete ")&&(e.className+=" complete")},a>100){var r=e.getAttribute("data-src");if(r&&a){e.className+=" loading";for(var o=artworkSizes[artworkSizes.length-1],i=0;i<artworkSizes.length;i++)if(a<=artworkSizes[i]){o=artworkSizes[i];break}var n=new Image;n.onload=function(){t.src=n.src,e.className=e.className.replace(" loading","")},n.onerror=function(){e.className=e.className.replace(" loading","")},n.src=r.replace("100x100",o+"x"+o)}}else t.onerror=function(){e.parentElement.removeChild(e)}},loadScript=function(e,a,t){var r=!1,o=document.createElement("script");o.type="text/javascript",o.async="async",o.onload=o.onreadystatechange=function(){r||this.readyState&&"complete"!=this.readyState||(r=!0,a&&a(t))};var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(o,i),o.src=e};if(grid){loadScript("https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js",layout,grid);for(var items=grid.children,i=0;i<items.length;i++)updateImage(items[i])}