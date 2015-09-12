function load_map_js(url_json)
{
    var margin = {
            top: 0,
            right: 120,
            bottom: 20,
            left: 50
    },
    width = 10000 - margin.right - margin.left,
    height = 400 - margin.top - margin.bottom;

    var i = 0,
        duration = 750,
        root;

    var tree = d3.layout
        .tree()
        .size([height,width])
    ;

    var diagonal = d3.svg.diagonal().projection(function(d)
    {
        return [d.y, d.x];
    });
    var svg = d3.select("#result").append("svg")
        .attr("width", width + margin.right + margin.left)
        .attr("height", "100%")
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    d3.json(url_json, function(error, home)
    {
      root = home;
      root.x0 = height / 2;
      root.y0 = 0;
      function collapse(d)
      {
        if (d.children)
        {
          d._children = d.children;
          d._children.forEach(collapse);
          d.children = null;
        }
      }

      root.children.forEach(collapse);
      update(root);
    });

    d3.select(self.frameElement).style("height", "800px");

    function update(source)
    {

        var levelWidth = [1];
        var childCount = function(level, n) {

              if(n.children && n.children.length > 0) {
                if(levelWidth.length <= level + 1) levelWidth.push(0);

                levelWidth[level+1] += n.children.length;
                n.children.forEach(function(d) {
                  childCount(level + 1, d);
                });
              }
            };
        childCount(0, root);
        var newHeight = d3.max(levelWidth) * 20; // 20 pixels per line
        if (newHeight < 400) newHeight = 400;
        tree.size([newHeight, width]);
        jQuery('#result').css('height',newHeight);

      // Compute the new tree layout.
      var nodes = tree.nodes(root).reverse(),
          links = tree.links(nodes);

      // Normalize for fixed-depth.
      nodes.forEach(function(d) { d.y = d.depth * 180; });

      // Update the nodes…
      var node = svg.selectAll("g.node")
          .data(nodes, function(d) { return d.id || (d.id = ++i); });
      // Enter any new nodes at the parent's previous position.
      var nodeEnter = node.enter().append("g")
          .attr("class", "node")
          .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
          .on("click", click)
          .on("dblclick", dbclick);


      nodeEnter.append("rect")
          .attr("rx", 1e-6)
          .attr("ry", 1e-6)
          .attr("width", 1e-6)
          .attr('height',1e-6)
          .style("fill", get_fill_color)
          .attr("class",function(d) { return d.style });

      nodeEnter.append("text")
          .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
          .attr("dy", ".35em")
          .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
          .text(function(d) { return d.name;  })
          .attr("uid",function(d) { return d.uid; })
          .attr("class",function(d) { return d.style })
          .style("fill-opacity", 1e-6);

      // Transition nodes to their new position.
      var nodeUpdate = node.transition()
          .duration(duration)
          .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

      nodeUpdate.select("rect")
          .attr("rx", function(d) { return d.style == "section" ? 1e-6 : 4.5 ; })
          .attr('ry', function(d) { return d.style == "section" ? 1e-6 : 4.5 ; })
          .attr('height',function(d) { return d.style == "section"   ? 7 : 9 ; })
          .attr('width',function(d)  { return d.style == "section"  ? 7 : 9 ; })
          .attr('y',-4)
          .style("fill", get_fill_color);

      nodeUpdate.select("text")
          .style("fill-opacity", 1);

      // Transition exiting nodes to the parent's new position.
      var nodeExit = node.exit().transition()
          .duration(duration)
          .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
          .remove();

      nodeExit.select("rect")
          .attr("rx", 1e-6)
          .attr('ry',1e-6)
          .attr('height',1e-6)
          .attr('width',1e-6)
          ;

      nodeExit.select("text")
          .style("fill-opacity", 1e-6);

      // Update the links…
      var link = svg.selectAll("path.link")
          .data(links, function(d) { return d.target.id; });

      // Enter any new links at the parent's previous position.
      link.enter().insert("path", "g")
          .attr("class", "link")
          .attr("d", function(d) {
            var o = {x: source.x0, y: source.y0};
            return diagonal({source: o, target: o});
          });

      // Transition links to their new position.
      link.transition()
          .duration(duration)
          .attr("d", diagonal);

      // Transition exiting nodes to the parent's new position.
      link.exit().transition()
          .duration(duration)
          .attr("d", function(d) {
            var o = {x: source.x, y: source.y};
            return diagonal({source: o, target: o});
          })
          .remove();

      // Stash the old positions for transition.
      nodes.forEach(function(d) {
        d.x0 = d.x;
        d.y0 = d.y;
      });


    }

    // Toggle children on click.
    function click(d)
    {
      if (d.children)
      {
        d._children = d.children;
        d.children = null;
      } else {
        d.children = d._children;
        d._children = null;
      }
      update(d);
    }
    function dbclick(d)
    {
        if (d.uid)
        {
            if (d.style=="section"){
                location.href="edit_section?id="+d.uid;
            } else if (d.style =="ondisk")
            {
                location.href="pageondisk?id="+d.uid;
            } else {
                location.href="edit_page?id="+d.uid;
            }
        }
    }
    function get_fill_color(d)
    {
        if (d._children && d.style=="ondisk")
        {
            return "#ff0";
        } else if (d._children)
        {
            return "lightsteelblue";
        } else {
            return "#fff";
        }
    }
    function redraw()
    {
          console.log("here", d3.event.translate, d3.event.scale);
          svg.attr("transform",
              "translate(" + d3.event.translate + ")"
              + " scale(" + d3.event.scale + ")");
    }
}