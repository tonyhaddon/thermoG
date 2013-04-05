
var margin = {top: 20, right: 20, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

var x = d3.time.scale()
    .range([0, width]);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");

var intLine = d3.svg.line()
    .x(function(d) { return x(d.g_datetime); })
    .y(function(d) { return y(d.g_intread); });

var extLine = d3.svg.line()
    .x(function(d) { return x(d.g_datetime); })
	.y(function(d) { return y(d.g_extread) });

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

d3.json("get_json.php", function(error, data) {
  data.forEach(function(d) {
    d.g_datetime = parseDate(d.g_datetime);
    d.g_intread = +d.g_intread;
  });



 x.domain(d3.extent(data, function(d) { return d.g_datetime; }));
 //y.domain(d3.extent(data, function(d) { return d.g_intread; }));
y.domain([-10,30]);

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g").
	selectAll("line").
	data(x.ticks(6)).
	enter().append("line").
	  attr("y1", 0).
	  attr("y2", height).
	  attr("x1",x).
	  attr("x2",x).
	  style("stroke", "#eee");

  svg.append("g").
	selectAll("line").
	data(y.ticks(8)).
	enter().append("line").
	  attr("y1", y).
	  attr("y2", y).
	  attr("x1",0).
	  attr("x2",width).
	  style("stroke", "#eee");

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Temp °C");

  svg.append("path")
      .datum(data)
      .attr("class", "line")
      .attr("d", intLine);

  svg.append("path")
      .datum(data)
      .attr("class", "line2")
      .attr("d", extLine);
});