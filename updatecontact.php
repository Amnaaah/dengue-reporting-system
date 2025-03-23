<?php
session_start();
	include 'dbcon.php';
	if (isset($_POST['change-contact'])) 
	{
		$UserID = $_SESSION['User_ID'];
		$contact = $_SESSION['contact'];

		$query = "UPDATE general_user SET Contact = '$contact' WHERE User_ID = '$UserID'";
		$result = mysqli_query($conn, $query);

		if ($result > 0) 
		{
			echo "<script> alert('Contact number Updated Successfully!');
			window.location.href = 'settings.php';</script>";
		}
	}
?>