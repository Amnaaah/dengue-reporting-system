<?php
session_start();
include "dbcon.php";

if (isset($_POST['btn-login'])) 
{
    $username = $_POST['username'];
    $phoneNum = $_POST['phone'];
    $password = $_POST['password'];

    $query = "SELECT User_ID, Division_ID FROM general_user WHERE U_Password = '$password' AND Username = '$username' AND Contact = '$phoneNum'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_array($result)) 
    {
        $userId = $row['User_ID'];
        $divisionId = $row['Division_ID'];
        $role = "";
  
        // Determine user role using conditional statements
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM PHI WHERE User_ID = '$userId'")) > 0) 
        {
            $role = "PHI";
        }
         elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Grama_Niladhari WHERE User_ID = '$userId'")) > 0) 
        {
            $role = "GN";
        }
         elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM MOH_Officer WHERE User_ID = '$userId'")) > 0) 
        {
            $role = "MOH";
        }
         elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Community_Police WHERE User_ID = '$userId'")) > 0) 
        {
            $role = "CP";
        }
         else 
        {
            $role = "GU";
        }

        // Save data in session
        $_SESSION['User_ID'] = $userId;  
        $_SESSION['role'] = $role;        
        $_SESSION['username'] = $username; 
        $_SESSION['division'] = $divisionId;

        setcookie('username', $username, time() + (60 * 24 * 60 * 60), "/"); 
        setcookie('phone', $phoneNum, time() + (60 * 24 * 60 * 60), "/"); 

        header("Location: home.php");
        exit();
    }
    else
    {
        echo "<script>alert('Invalid credentials. Please try again!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
    }

    mysqli_close($conn);
}
?>
