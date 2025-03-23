<?php
include 'dbcon.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $comment =  $_POST['comment']; 
    $caseID = (int) $_POST['selectedCaseID']; 

    $query = "UPDATE cases SET Comments = '$comment' WHERE Case_ID = $caseID";
    $result = mysqli_query($conn, $query);

    if ($result) 
    {
        echo "Comment added successfully.";
    }
    else
    {
        echo "Error adding comment" . mysqli_error($conn);
    }

    header("location: view_all_cases.php");

}
?>
