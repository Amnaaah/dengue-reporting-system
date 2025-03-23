<?php
session_start();
include 'dbcon.php';

if (!isset($_SESSION['User_ID'])) 
{
    echo "Please login to generate reports";
    header("Location: login.php");
    exit();
}

$divisionId = $_SESSION['divisionId']; 

//getting current month & year by default
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : date("m"); 
$selectedYear = isset($_POST['year']) ? $_POST['year'] : date("Y");   

$query = "SELECT * FROM cases WHERE Division_Id = '$divisionId' 
          AND MONTH(Date_Time) = '$selectedMonth' 
          AND YEAR(Date_Time) = '$selectedYear'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <title>Monthly Report</title>
    <link rel="stylesheet" href="viewallcasesstyle.css">
<style>

body
{
    background-color: #eef5e6; 
    font-family: 'Lora', serif;
}

h1
{
    color: #2c4f2e; 
}

select, button 
{
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #2c4f2e;
    background-color: #4c7737; 
    color: white;
    font-weight: bold;
    cursor: pointer;
}

button:hover
{
    background-color: #3b5d28; 
}
</style>
</head>
<body>

<div class="navbar">
    <a href="javascript:history.back()">Go Back</a> 
    <a href="home.php">Home</a>
    <a href="view_all_cases.php">View all Cases</a>
</div>
<br><br><br><br>

<form method="post">
    <label for="month">Select Month:</label>
    <select name="month" id="month">
        <?php 
        //loop through the months
        for ($m = 1; $m <= 12; $m++) 
        {
            $monthValue = str_pad($m, 2, "0", STR_PAD_LEFT); // Convert to 2-digit format (e.g., 01, 02)
            $selected = ($monthValue == $selectedMonth) ? "selected" : ""; 
            echo "<option value='$monthValue' $selected>" . date("F", mktime(0, 0, 0, $m, 1)) . "</option>"; // Showing month name
        }
        ?>
    </select>

    <label for="year">Select Year:</label>
    <select name="year" id="year">
        <?php 
        $currentYear = date("Y"); 
        for ($y = $currentYear; $y >= $currentYear - 5; $y--) 
        { 
            $selected = ($y == $selectedYear) ? "selected" : ""; // Check if it's the selected year
            echo "<option value='$y' $selected>$y</option>";
        }
        ?>
    </select>

    <button type="submit">Generate Report</button>
</form>

<br>

<table border="1">
    <thead>
        <tr>
            <th>Case ID</th>
            <th>Description</th>
            <th>Status</th>
            <th>Comments</th>
            <th>Reporter ID</th>
            <th>Resolved Police ID</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($result)) : ?>
            <tr>
                <td><?= $row['Case_ID'] ?></td>
                <td><?= $row['Description'] ?></td>
                <td><?= $row['Verify_Status'] ?></td>
                <td><?= $row['Comments'] ?></td>
                <td><?= $row['User_ID'] ?></td>
                <td><?= $row['ResolvedBy_Police'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
