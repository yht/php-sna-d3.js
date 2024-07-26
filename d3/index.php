<?php
require_once('../db/conn.php');

// Fetch relationships from the entity_relationships table
$relationshipsSql = "SELECT e1.name AS company, e2.name AS owner 
                     FROM entity_relationships er 
                     JOIN entities e1 ON er.entity_id = e1.id
                     JOIN entities e2 ON er.related_entity_id = e2.id";
$relationshipsResult = $conn->query($relationshipsSql);

$data = [];
if ($relationshipsResult->num_rows > 0) {
    while ($row = $relationshipsResult->fetch_assoc()) {
        $data[] = [
            'company' => $row['company'],
            'owner' => $row['owner']
        ];
    }
} else {
    echo "0 results";
}

$conn->close();

// Convert PHP array to JSON
$jsonData = json_encode($data);

// Output the JSON data
#header('Content-Type: application/json');
#echo $jsonData;
?>


<!DOCTYPE html>
<html>
<head>
  <script src="d3.v6.min.js"></script>
  <style>
    svg {
      display: block;
    }
    text {
      font-family: Arial, sans-serif;
      font-size: 12px;
      fill: #333;
    }
  </style>
  <title>D3</title>
</head>
<body>
  <button id="reload-button">Reload Page</button>
  <div id="graph"></div>
  <script>
    var data = <?php echo $jsonData; ?>;

    // Process data into nodes and links
    var nodes = {};
    var links = [];

    data.forEach(function(d) {
      if (!nodes[d.company]) {
        nodes[d.company] = { id: d.company };
      }
      if (!nodes[d.owner]) {
        nodes[d.owner] = { id: d.owner };
      }
      links.push({ source: d.company, target: d.owner });
    });

    nodes = Object.values(nodes);

    // Set up SVG dimensions
    var width = window.innerWidth - 20;
    var height = window.innerHeight - 20;

    var svg = d3.select("#graph").append("svg")
        .attr("width", width)
        .attr("height", height);

    var link = svg.append("g")
        .selectAll("line")
        .data(links)
        .enter().append("line")
        .attr("stroke-width", 2)
        .attr("stroke", "#999");

    var node = svg.append("g")
        .selectAll("circle")
        .data(nodes)
        .enter().append("circle")
        .attr("r", 10)
        .attr("fill", "#69b3a2");

    var label = svg.append("g")
        .selectAll("text")
        .data(nodes)
        .enter().append("text")
        .attr("dy", -10)  // Offset the label above the node
        .attr("dx", -20)  // Offset the label above the node
        .text(d => d.id)
        .style("font-size", "12px");

    var simulation = d3.forceSimulation(nodes)
        .force("link", d3.forceLink(links).id(d => d.id).distance(50))
        .force("charge", d3.forceManyBody().strength(-100))
        .force("center", d3.forceCenter(width / 2, height / 2));

    simulation.on("tick", () => {
      link
        .attr("x1", d => d.source.x)
        .attr("y1", d => d.source.y)
        .attr("x2", d => d.target.x)
        .attr("y2", d => d.target.y);

      node
        .attr("cx", d => d.x)
        .attr("cy", d => d.y);

      label
        .attr("x", d => d.x)
        .attr("y", d => d.y);
    });

    // Update SVG size on window resize
    window.addEventListener('resize', function() {
      width = window.innerWidth - 20;
      height = window.innerHeight - 20;
      svg.attr("width", width)
         .attr("height", height);

      simulation.force("center", d3.forceCenter(width / 2, height / 2));
      simulation.alpha(1).restart(); // Restart simulation to adjust positions
    });

    document.getElementById('reload-button').addEventListener('click', function() {
            window.location.reload();
        });
  </script>
</body>
</html>
