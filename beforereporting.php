<?php
session_start();

if (isset($_SESSION['username'])) 
{
    header("Location: report.php");
}
else 
{   
    header("Location: login.php");
    exit(); 
}
?>
