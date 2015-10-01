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



svg.click = function(d){
    d3.event.stopPropagation();
    if(svg.active.node() === this) return svg.reset();
    svg.reset();
    dom.title.textContent = d["collectionName"] + ' — ' + d["artistName"];
    svg.active = d3.select(this).classed("active", true);
    
    if(svg.active.attr("data-zoomed") == 0){
       
        svg.active.attr("data-zoomed", 1);     
        var img = d3.select("pattern#artwork-"+svg.active.attr("data-id") + " image");
        img.attr('xlink:href', d["artworkUrl100"].replace("100x100", svg.bigImageSize+"x"+svg.bigImageSize))    
    }

    
    svg.active.moveToFront()
                    .transition()
                    .duration(500)
                     .style("stroke-width", svg.pad)
                    .attr("r", Math.floor(Math.min(svg.w, svg.h)/2) - svg.pad*2)
                    .attr("cy", Math.floor(svg.h/2))
                    .attr("cx", Math.floor(svg.w/2));
    
};

svg.filter = function(d){
    d3.event.stopPropagation();
    if(svg.genre.node() === this) return svg.clearFilters();
    svg.genre.classed("active", false);
    svg.genre  = d3.select(this).classed("active", true);
    svg.curGenre = svg.genre.attr("data-id");
    var titles = dom.title.getAttribute("data-text").split("100 ");
    dom.title.textContent = titles[0] + "100 " + svg.curGenre + " " +titles[1];
    for (var i = 0; i < svg.dataByGenre.length; i++) {
        if(svg.dataByGenre[i].key === svg.curGenre){
            svg.data =svg.dataByGenre[i].values; 
            break;
        }
    };
    
    svg.drawD3();
};

svg.clearFilters = function(){
    if(svg.genre.empty()) return;

    svg.genre.classed("active", false);
    svg.genre = d3.select(null);

    svg.data = topAlbums;
    dom.title.textContent = dom.title.getAttribute("data-text");
    svg.curGenre = null;
    svg.drawD3();
}

svg.reset = function(){
    if(svg.active.empty()) return;

    
    svg.active.transition()
            .duration(500)
            .attr("cx", function (d) { return svg.x(+d[svg.xKey]); })
            .attr("cy", function (d) { return svg.y(+d[svg.yKey]); })
            .style("stroke-width", Math.floor(svg.radius/10))
            .attr("r", function (d) { return svg.r(+d[svg.rKey]); });
    svg.active.classed("active", false);
    svg.active = d3.select(null);

    if(svg.curGenre){
       var titles = dom.title.getAttribute("data-text").split("100 ");
        dom.title.textContent = titles[0] + "100 " + svg.curGenre + " " +titles[1];  
    }else{
        dom.title.textContent = dom.title.getAttribute("data-text");
    }
   

};

svg.hover = function(d){
    if(svg.active.node() === this) return;
    d3.select(this).moveToFront()
                    .transition()
                    .duration(500)
                    .style("stroke-width", Math.floor(svg.radius/10))
                    .attr("cx", svg.x(+d[svg.xKey]))
                    .attr("cy", svg.y(+d[svg.yKey]))
                    .attr("r",  svg.radius);
   
};

svg.hoverOut = function(d){
    if(svg.active.node() === this) return;

    

    var cur = d3.select(this);
    cur.transition()
        .duration(250)
        .style("stroke-width", Math.floor(svg.radius/10))
        .attr("cx", svg.x(+d[svg.xKey]))
        .attr("cy", svg.y(+d[svg.yKey]))
        .attr("r", svg.r(+d[svg.rKey]));
    setTimeout(function(){cur.moveToBack();}, 251);

};

svg.drawD3 = function(){
    //console.log("Drawing up D3");

    // console.log(svg.data);
    
    svg.pad = dom.links[0].offsetLeft
    svg.w = dom.graph.offsetWidth -  (svg.pad*2);
    svg.h = dom.graph.offsetHeight - dom.footer.offsetHeight - dom.title.offsetHeight;
    svg.bigImageSize  = global.getImageWidth(Math.min(svg.w, svg.h) - svg.pad*2);
    //console.log(svg.bigImageSize);


   
    svg.radius =  Math.max(Math.min(Math.min(svg.w, svg.h)/10, 50), 25);
    //console.log(svg.radius);

    svg.xDomain = d3.extent(svg.data, function(d) { return +d[svg.xKey]; });  

    

    svg.yDomain = d3.extent(svg.data, function(d) { return +d[svg.yKey]; }); 

    svg.rDomain = d3.extent(svg.data, function(d) { return +d[svg.rKey]; });  


    svg.x = d3.scale.linear().domain(svg.xDomain).range([svg.radius+svg.pad*2, svg.w-svg.radius]),
    svg.y = d3.scale.linear().domain(svg.yDomain).range([ svg.h-svg.radius-svg.pad*2,svg.radius]),

    svg.xA = d3.scale.linear().domain([0,100]).range([0, svg.w]),
    svg.yA = d3.scale.linear().domain([0,100]).range([svg.h, 0 ]),

    svg.r = d3.scale.linear().domain(svg.rDomain).range([ svg.radius/3,svg.radius]);

    svg.tDomain = d3.extent(svg.data, function(d) { return +d[svg.tKey]; });  
    svg.t = d3.scale.linear().domain(svg.tDomain).range([ 1000, 0]);


    svg.xAxis.scale(svg.xA);
    svg.yAxis.scale(svg.yA);


    svg.canvas.attr("width", svg.w).attr("height", svg.h);

    svg.xAxisGroup.attr("transform", "translate(5, "+(svg.h -5)+")").call(svg.xAxis);
    svg.yAxisGroup.call(svg.yAxis);

    svg.legend.attr("transform", function(d, i) { return "translate(0," + i * svg.pad*1.5 + ")"; });
    svg.legend.selectAll("text").attr("x", svg.w - svg.pad*1.5).attr("y",svg.pad/2);
    svg.legend.selectAll("circle").attr("cx", svg.w - svg.pad/2).attr("cy", svg.pad/2).attr("r", svg.pad/2);


    svg.xLabel.attr("x", svg.w ).attr("y", svg.h - svg.pad*1.2);
    svg.yLabel.attr("y", svg.pad);




     svg.circles = svg.canvas.selectAll(".circle").data(svg.data, function(d) { return d["collectionId"]; });

    var newCircles = svg.circles.enter()// new
                .append("circle")
                // .on("mouseover", svg.hover)
                // .on("mouseout",svg.hoverOut)
                // .on("click",svg.click)
                .attr("class", "circle")
                .style("fill", function(d) { return "url(#artwork-" + d["collectionId"] + ")";})
                .style("stroke", function(d) { return svg.color(svg.cValue(d));})
                .attr("data-id",function(d) { return d["collectionId"];})
                .attr("data-zoomed",0)
                .style("stroke-width", Math.floor(svg.radius/10))
                .attr("cx", function (d) { return svg.x(+d[svg.xKey]); })
                .attr("cy", function (d) { return svg.y(+d[svg.yKey]); })
                .attr("r", 5)
                .style("fill-opacity", 1e-6)
                .style("stroke-opacity", 1e-6)
                ;             


    newCircles.on("mouseover", svg.hover);
    newCircles.on("mouseout",svg.hoverOut);
    newCircles.on("click",svg.click);       
                
    newCircles.append("svg:title")
                .text(function(d) { return d["collectionName"] + ' — ' + d["artistName"]; });          
                
                

     svg.circles.transition()// update
                .duration(500)
                .delay(function (d) { return svg.t(+d[svg.tKey]); })
                .attr("cx", function (d) { return svg.x(+d[svg.xKey]); })
                .attr("cy", function (d) { return svg.y(+d[svg.yKey]); })
                .attr("r", function (d) { return svg.r(+d[svg.rKey]); })
                .style("stroke-width", Math.floor(svg.radius/10))             
                .style("fill-opacity", 1)
                .style("stroke-opacity", 1);



     svg.circles.exit() // old
                .transition()
                .duration(500)
                .delay(function (d) { return svg.t(+d[svg.tKey]); })
                .attr("r", 5)                
                .style("fill-opacity", 1e-6)
                .style("stroke-opacity", 1e-6)
                .remove();

    


     
                
    
   
};

////////////////////////////////////////////////////////////////////////////////////


svg.setupD3 = function(container){

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
    svg.yKey = "totalPlays";
    svg.xKey = "firstPlay";
    svg.rKey = "numberOfTracks";
    svg.cKey = "primaryGenreName";
    svg.tKey = "lastPlay";
    svg.active = d3.select(null);
    svg.genre  = d3.select(null);


    svg.data = topAlbums;
    svg.dataByGenre = d3.nest()
                            .key(function(d) { return d[svg.cKey]; })
                            .entries(svg.data);
   
   
    svg.cDomain = d3.scale.ordinal()
               .domain(svg.data.map( function (d) { return d[svg.cKey]; }));


    // setup fill color
    svg.cValue = function(d) { return d[svg.cKey];},
    svg.color = d3.scale.category20().domain(svg.cDomain.domain());



    svg.canvas = d3.select("#graph").append("svg");
    svg.canvas.on("click",svg.reset);


    svg.xAxisGroup = svg.canvas.append("g")
    .attr("class", "axis");
 
    svg.yAxisGroup = svg.canvas.append("g")
        .attr("class", "axis")
        .attr("transform", "translate(5, -5)");


    svg.xAxis = d3.svg.axis().orient("bottom").tickFormat(function (d) { return ''; });
    svg.yAxis = d3.svg.axis().orient("left").tickFormat(function (d) { return ''; });
        
 
    svg.canvas.append("defs").selectAll("pattern").data(svg.data)
        .enter()
        .append('pattern')
        .attr('id', function(d) { return "artwork-"+d["collectionId"];})
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
        .attr('height', 100);
                         
   
    svg.legend = svg.canvas.selectAll(".legend")
        .data(svg.color.domain())
        .enter().append("g")
        .attr("class", "legend")
        .attr("data-id", function(d) { return d;});
        

    svg.legend.on("click",svg.filter);

    // draw legend colored rectangles
    svg.legend.append("circle")
      .style("fill", svg.color);

    // draw legend text
    svg.legend.append("text")
      .attr("dy", "0.35em")
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
                .attr("x", 0)
                .style("text-anchor", "end")
                .text("amount of plays");
    svg.drawD3();
    window.addEventListener("resize",  svg.drawD3);
};