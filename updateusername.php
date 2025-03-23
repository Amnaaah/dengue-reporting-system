<?php
session_start();
	include 'dbcon.php';
	if (isset($_POST['change-username'])) 
	{
		$UserID = $_SESSION['User_ID'];
		$Username = $_POST['username'];

		$query = "UPDATE general_user SET Username = '$Username' WHERE User_ID = '$UserID'";
		$result = mysqli_query($conn, $query);

		if ($result > 0) 
		{
			echo "<script> alert('Username Updated Successfully!');
			window.location.href = 'settings.php';</script>";
        	$_SESSION['username'] = $Username; 
		}
	}
?>