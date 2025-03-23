<?php
include 'dbcon.php';
session_start();

if (isset($_POST['btn-register'])) 
{

    $username = $_POST['username'];
    $phonenum = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['cmbusertype'];
    $division = $_POST['cmbdivision'];
    $password = $_POST['password'];

    // Query to insert into general_user
    $generalUserQuery = "INSERT INTO general_user (U_Password, Username, Contact, Address, Division_ID) 
                         VALUES ('$password', '$username', '$phonenum', '$address', '$division')";

    $result = mysqli_query($conn, $generalUserQuery);

    if ($result) 
    {
        $userId = mysqli_insert_id($conn);

        $_SESSION['username'] = $username;
        $_SESSION['User_ID'] = $userId;
        $_SESSION['cmbdivision'] = $division;
        $_SESSION['role'] = $role;

        switch ($role) 
        { 
            case 'PHI':
                $licenseNo = $_POST['license_no'];
                $specialization = $_POST['specialization'];
                $workingPeriod = $_POST['working_period'];

                $phiQuery = "INSERT INTO PHI (User_ID, License_No, Specialization, Working_Period) 
                             VALUES ('$userId', '$licenseNo', '$specialization', '$workingperiod')";
                mysqli_query($conn, $phiQuery);
                break;

            case 'GN':
                $gnCode = $_POST['gn_code'];
                $workingPeriod = $_POST['Workingperiod'];
                $emContact = $_POST['em_contact'];

                $gnQuery = "INSERT INTO grama_niladhari (User_ID, GN_Code, Working_Period, Emergency_Contact) 
                            VALUES ('$userId', '$gnCode', '$workingPeriod', '$emContact')";
                mysqli_query($conn, $gnQuery);
                break;

            case 'MOH':
                $mohRegId = $_POST['moh_reg_id'];
                $specialization = $_POST['specialization'];
                $emContact = $_POST['em_contact'];

                $mohQuery = "INSERT INTO moh_officer (User_ID, Registration_No, Specialization, Emergency_Contact) 
                             VALUES ('$userId', '$mohRegId', '$specialization', '$emContact')";
                mysqli_query($conn, $mohQuery);
                break;

            case 'CP':
                $batchNumber = $_POST['batch_number'];
                $stationName = $_POST['station_name'];

                $comPoliceQuery = "INSERT INTO community_police (User_ID, Batch_No, Station_Name) 
                                   VALUES ('$userId', '$batchNumber', '$stationName')";
                mysqli_query($conn, $comPoliceQuery);
                break;
        }

        header("Location: home.php");
         exit();
        }
     else 
    {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} 
else 
{
    echo "Please fill out the registration form!";
    header("Location: register1.php");
}
?>
