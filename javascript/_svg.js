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
    svg.pad = dom.links[0].offsetLeft
    svg.w = dom.graph.offsetWidth -  (svg.pad*2);
    svg.h = dom.graph.offsetHeight - dom.footer.offsetHeight - dom.title.offsetHeight;
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

    svg.x = d3.scale.linear().domain(svg.xDomain).range([svg.radius+svg.pad*2, svg.w-svg.radius]),
    svg.y = d3.scale.linear().domain(svg.yDomain).range([ svg.h-svg.radius-svg.pad*2,svg.radius]),

    svg.xA = d3.scale.linear().domain([0,100]).range([0, svg.w]),
    svg.yA = d3.scale.linear().domain([0,100]).range([svg.h, 0 ]),

    svg.r = d3.scale.linear().domain(svg.rDomain).range([ svg.radius/3,svg.radius]);
    svg.t = d3.scale.linear().domain(svg.tDomain).range([ 0, 10000]);


    svg.xAxis.scale(svg.xA);/*.ticks(6)
        .tickFormat(function (d, i) {
            return ['', 'few plays','', 'quite a bit', '', 'a lot'][i];
        });*/
    svg.yAxis.scale(svg.yA);


    svg.canvas.attr("width", svg.w).attr("height", svg.h);

    svg.xAxisGroup.attr("transform", "translate("+5+", "+(svg.h -5)+")").call(svg.xAxis);
    svg.yAxisGroup.call(svg.yAxis);
    svg.legend.selectAll("text").attr("x", svg.w - svg.pad*1.5).attr("y",svg.pad/2);
    svg.legend.selectAll("circle").attr("cx", svg.w - svg.pad/2).attr("cy", svg.pad/2);


    svg.xLabel.attr("x", svg.w ).attr("y", svg.h - svg.pad*1.2);
    svg.yLabel.attr("x", 0).attr("y", svg.pad);

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

////////////////////////////////////////////////////////////////////////////////////

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
    svg.pad = dom.links[0].offsetLeft
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
        .attr("transform", "translate("+5+", -5)");


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
                .duration(500).style("stroke-width", 2).attr("r", function (d) { return svg.radius+1; });
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
        .attr("transform", function(d, i) { return "translate(0," + i * svg.pad*1.5 + ")"; });

    // draw legend colored rectangles
    svg.legend.append("circle")
      .attr("r", svg.pad/2)
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
                .style("text-anchor", "end")
                .text("amount of plays");
    global.drawD3();
    window.addEventListener("resize",  global.drawD3);
};