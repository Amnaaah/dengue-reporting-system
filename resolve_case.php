<?php
session_start();
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Get the data from the form submission
    $officerID = $_POST['officerID'];
    $selectedCaseID = $_POST['selectedCaseID'];
    $officerName = $_POST['officerName'];

    // Update the case in the database
    $updateQuery = "UPDATE cases SET ResolvedBy_Police = '$officerID' WHERE Case_ID = '$selectedCaseID'";
    if (mysqli_query($conn, $updateQuery)) 
    {
        echo"<script> alert('Officer assigned Successfully!');
        window.location.href = 'view_all_cases.php';</script>";
        exit();
    } 
    else 
    {
        echo "Error updating case: " . mysqli_error($conn);
    }
}
?>
