<?php
include 'dbcon.php'; 
session_start(); 

if (isset($_SESSION['User_ID'])) 
{
    $userID = $_SESSION['User_ID'];

    $query = "SELECT * FROM cases WHERE ResolvedBy_Police = '$userID'";
    $result = mysqli_query($conn, $query);

    if (!$result) 
    {
        echo "Query Error: " . mysqli_error($conn);
        exit();
    }

    $reportedIDs = [];
    while ($case = mysqli_fetch_array($result)) 
    {
        $reportedIDs[] = $case['User_ID']; 
    }

    $reportedIDs = array_unique($reportedIDs);

    if (!empty($reportedIDs)) 
    {
        // Convert the array to a comma-separated string for SQL IN clause
        $reportedIDsString = implode("', '", $reportedIDs);
        
        // Query to select reporter details
        $reporterQuery = "SELECT * FROM general_user WHERE User_ID IN ('$reportedIDsString')";
        $reporterResult = mysqli_query($conn, $reporterQuery);

        if (!$reporterResult) 
        {
            echo "Query Error: " . mysqli_error($conn);
            exit();
        }

    }
     else 
    {
        echo "<script> alert('No user details found');
         window.location.href = 'account.php';</script>";
    }
}
else 
{
    echo "User is not logged in.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reported Users</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <style>
        .reporter-details table 
        {
            width: 70%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .reporter-details th, .reporter-details td 
        {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .reporter-details th 
        {
            background: #588157;
            color: white;
        }

        .reporter-details tr 
        {
            transition: background-color 0.3s ease;
        }

        .reporter-details tr.selected 
        {
            background-color: #99c2a2; 
        }

        .navbar 
        {
            background-color: #84a98c; 
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            height: 50px;
        }

        .navbar a 
        {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #8fb996; /* Pale Green Border */
            border-radius: 6px;
            margin: 0 10px; /* Spacing between buttons */
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        .navbar p
        {
            color: white;
            text-align: left;
            font-size: 20px;
        }

        .navbar a:hover 
        {
            background: #6a994e;
            border-color: #bfd8b7; 
            transform: translateY(-2px); 
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <p>Reported Users</p>
    <a href="javascript:history.back()"> Go Back</a>
    <a href="home.php">Home</a>
</div>
<br>
<br>
<br><br><br>

        <div class="reporter-details">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Address</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($reporter = mysqli_fetch_assoc($reporterResult)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($reporter['User_ID']) . '</td>';
                        echo '<td>' . htmlspecialchars($reporter['Username']) . '</td>'; 
                        echo '<td>' . htmlspecialchars($reporter['Contact']) . '</td>'; 
                        echo '<td>' . htmlspecialchars($reporter['Address']) . '</td>'; 
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

