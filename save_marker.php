<?php
include 'dbcon.php';

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Get the latitude and longitude from the POST request
$lat = $_POST['lat'];
$lng = $_POST['lng'];

$sql = "INSERT INTO markers (lat, lng) VALUES ('$lat', '$lng')";

// Execute the query
if ($conn->query($sql) === TRUE) 
{
    echo "Marker saved successfully!";
}
else
{
    echo "Error saving marker: " . $conn->error;
}

$verify_sql = "SELECT * FROM markers WHERE lat = '$lat' AND lng = '$lng'";
$result = $conn->query($verify_sql);

if ($result->num_rows > 0) 
{
    echo "Data verified and saved in the database.";
} 
else 
{
    echo "Data not found in the database.";
}

// Close the database connection
$conn->close();
?>