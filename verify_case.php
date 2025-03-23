<?php
include 'dbcon.php';
session_start();

if (isset($_POST['selectedCaseID'])) 

{
    $selectedCase = $_POST['selectedCaseID'];
    $role = $_SESSION['role'];
    $userId = $_SESSION['User_ID'];

    // Fetch current verification status
    $fetchQuery = "SELECT VerifiedBy_PHI, VerifiedBy_GN FROM cases WHERE Case_ID = '$selectedCase'";
    $fetchResult = mysqli_query($conn, $fetchQuery);

    if (!$fetchResult) 
    {
        echo "Query Error: " . mysqli_error($conn);
        exit();
    }

    $row = mysqli_fetch_assoc($fetchResult);
    $verifiedByPHI = $row['VerifiedBy_PHI'];
    $verifiedByGN = $row['VerifiedBy_GN'];

    // Update verification status based on role
    if ($role === "PHI") 
    {
        $phiQuery = "UPDATE cases SET VerifiedBy_PHI = '$userId' WHERE Case_ID = '$selectedCase'";
        mysqli_query($conn, $phiQuery);
        $verifiedByPHI = $userId;
         echo "<script> alert('Case verified by PHI successfully!');</script>";
    }
    elseif ($role === "GN")
    {
        $gnQuery = "UPDATE cases SET VerifiedBy_GN = '$userId' WHERE Case_ID = '$selectedCase'";
        mysqli_query($conn, $gnQuery);
        $verifiedByGN = $userId; 
        echo "<script> alert('Case verified by Grama Niladhari successfully!');</script>";
    }
    else
    {
        echo "<script> alert('Couldn't verify case!');</script>";
        exit();
    }

    // If both PHI and GN have verified, update Verify_Status
    if ($verifiedByPHI && $verifiedByGN) 
    {
        $updateStatusQuery = "UPDATE cases SET Verify_Status = 'Verified' WHERE Case_ID = '$selectedCase'";
        mysqli_query($conn, $updateStatusQuery);
    }
    else
    {
        $updateStatusQuery = "UPDATE cases SET Verify_Status = 'Pending' WHERE Case_ID = '$selectedCase'";
        mysqli_query($conn, $updateStatusQuery);
    }

    echo"<script>window.history.back();</script>";
    exit();
}
?>
