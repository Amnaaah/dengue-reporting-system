<?php
session_start();
include "dbcon.php"; 

$divisionId = $_SESSION['divisionId']; 

// Update query for case notifications
$queryCase = "UPDATE notification 
              SET Noti_Status = 'Read' 
              WHERE Case_ID IN (SELECT Case_ID FROM cases WHERE Division_ID = '$divisionId')";

// Update query for outbreak notifications
$queryOutbreak = "UPDATE notification 
                  SET Noti_Status = 'Read' 
                  WHERE Noti_Status = 'Unread' AND Case_ID = '$divisionId'"; 

// Execute the case notification update
if (mysqli_query($conn, $queryCase)) 
{
    // Check if any outbreak notifications need to be updated
    if (mysqli_query($conn, $queryOutbreak)) 
    {
        sleep(5);
        header("Location: home.php");
        exit();
    } 
    else 
    {
        echo "Error updating outbreak notifications: " . mysqli_error($conn);
    }
} 
else 
{
    echo "Error updating case notifications: " . mysqli_error($conn);
}
?>
