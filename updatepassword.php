<?php 
session_start();
	include 'dbcon.php';
	if (isset($_POST['change-password'])) 
	{
		$UserID = $_SESSION['User_ID'];
		$oldpassword = $_POST['currentPassword'];
	    $newpassword = $_POST['newPassword'];

	    if ($oldpassword == $newpassword) 
	    {
	    	echo"<script> alert('Please enter a new password!');</script>";
	    }
	    else
	    {
	    	$query = "UPDATE general_user SET U_Password = '$newpassword' WHERE User_ID = '$UserID'";
			$result = mysqli_query($conn, $query);

			if ($result > 0) 
			{
				echo "<script> alert('Password Updated Successfully!');
				window.location.href = 'settings.php';</script>";
			}
		
		}
	}

 ?>