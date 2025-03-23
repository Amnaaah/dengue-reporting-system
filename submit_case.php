<?php
include 'dbcon.php';
session_start();

if (isset($_POST['submit-button'])) 
{
    if (!isset($_SESSION['User_ID'])) 
    {
        die("Error: User not logged in.");
    }

    $userId = $_SESSION['User_ID'];
    $division = $_POST['cmbdivision'];
    $description = ($_POST['description']);
    $location =($_POST['location']);
    date_default_timezone_set('Asia/Colombo');
    $date_time = date('Y-m-d H:i:s');   
    $status = "Pending";
    $target_dir = "uploads/";

    // Validate file upload
    if (!isset($_FILES["imagefile"]) || $_FILES["imagefile"]["error"] != UPLOAD_ERR_OK) 
    {
        die("Error: File upload failed!");
    }

    // Generates unique filename to prevent conflicts
    $imageFileName = uniqid() . "_" . basename($_FILES["imagefile"]["name"]);
    $target_file = $target_dir . $imageFileName;

    // Create the target directory if it doesn't exist
    if (!file_exists($target_dir)) 
    {
        mkdir($target_dir, 0777, true);
    }

    if (!move_uploaded_file($_FILES["imagefile"]["tmp_name"], $target_file)) 
    {
        die("Error: Could not save uploaded file.");
    }


    $sql = "INSERT INTO cases (Date_Time, Image_Src, Description, Location, User_ID, Verify_Status,Division_ID )  
        VALUES ('$date_time', '$target_file', '$description', '$location','$userId','$status',$division)";
 
     $results = mysqli_query($conn, $sql);


   if ($results) 
{
    // Capture the Case ID from the last insert operation
    $CaseID = mysqli_insert_id($conn);

    $notifySql = "INSERT INTO notification (Date_Time, Noti_Status, Message, Case_ID) 
                  VALUES ('$date_time', 'Unread', 'New case reported', '$CaseID')";

    mysqli_query($conn, $notifySql);

    echo "<script>
        alert('Report submitted successfully!');
        window.location.href = 'home.php';
    </script>";
    exit(); 
    } 
    else 
    {
        echo "Error: Could not submit report.";
    }

    mysqli_close($conn);
} 
else 
{
    echo "Invalid request.";
}
?>
