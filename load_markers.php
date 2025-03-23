<?php
// Include the database connection file
include 'dbcon.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch markers from the database
$sql = "SELECT lat, lng FROM markers";
$result = $conn->query($sql);

// Create an array to store markers
$markers = [];

// If there are results, fetch each row as an associative array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $markers[] = $row; // Add each marker to the markers array
    }
}

// Convert markers array to a script that can be inserted into the HTML
echo "<script>";
foreach ($markers as $marker) {
    // Add each marker to the map with the pulsating effect class
    echo "L.circleMarker([" . $marker['lat'] . ", " . $marker['lng'] . "], {
        radius: 8, 
        fillColor: '#2c5530', 
        color: '#2c5530', 
        weight: 1, 
        opacity: 1, 
        fillOpacity: 0.7, 
        className: 'pulsating-marker' // Custom class for pulsating effect
    }).addTo(map);";
}
echo "</script>";


// Close the database connection
$conn->close();
?>
