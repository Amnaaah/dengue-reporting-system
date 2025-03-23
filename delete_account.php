<?php
include 'dbcon.php';
session_start(); 

if (isset($_SESSION['User_ID'])) 
{
    $userId = $_SESSION['User_ID'];

    $query = "DELETE FROM general_user WHERE User_ID = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result) 
    {
        echo "<script>alert('Account Deleted Successfully!');</script>";
        session_destroy();
        header("location: home.php");
    }
    else
    {
        echo "<script>alert('Sorry, could not delete account!');</script>";
    }
}
else
{
    echo "<script>alert('Please login to continue!');</script>";
    header("location: login.php");

}
?>
