<?php
include 'dbcon.php';

	if (isset($_POST['addDistrict'])) 
	{
		$disname = $_POST['districtname'];
		$query = "INSERT INTO district WHERE District_Name = '$disname'";
		$res = mysqli_query($conn, $query);
		if($res > 0)
		{
			echo "<script>alert('District Added Successfully!');</script>
			window.location.href = 'manage_div_dis.php';</script>";
		}
	}
	else if (isset($_POST['addDivision'])) 
	{
		$divname = $_POST['division'];
		$disid = $_POST['cmbdistrict'];
		$query = "INSERT INTO division (Division_Name,District_ID) VALUES('$divname','$disid')"; 
		$res = mysqli_query($conn, $query);
		if($res > 0)
		{
			echo "<script>alert('Division Added Successfully!');
			window.location.href = 'manage_div_dis.php';</script>";
		}
	}
?>