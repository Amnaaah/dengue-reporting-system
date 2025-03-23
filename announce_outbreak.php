<?php
include 'dbcon.php';

if (isset($_POST['announce'])) 
{
    $divisionID = $_POST['division_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $date_time = date('Y-m-d H:i:s');

    $notifySql = "INSERT INTO notification (Date_Time, Noti_Status, Message, Case_ID) 
                  VALUES ('$date_time', 'Unread', '$message', '$divisionID')";

    if (mysqli_query($conn, $notifySql)) 
    {
        echo "<script>
            alert('Outbreak announcement sent successfully!');
            window.location.href = 'account.php';
        </script>";
    }
    else 
    {
        echo "Error: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announce Outbreak | DenGuard</title>
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="reportstyle.css"> 
</head>

<body>
<div class="form-container">
        <h2>Announce Dengue Outbreak</h2>
        
        <form action="" method="POST">
            <label>Select Division:</label>
            <select name="division_id" required>
                <option value="" disabled selected>Select Division</option>
                <?php
                $query = "SELECT Division_ID, Division_Name FROM division";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_array($result)) 
                {
                    echo "<option value='{$row['Division_ID']}'>{$row['Division_Name']}</option>";
                }
                ?>
            </select>

            <label>Message:</label>
            <textarea name="message" required></textarea>

            <button type="submit" name="announce">Send Announcement</button>
        </form>
    </div>
</body>
</html>
