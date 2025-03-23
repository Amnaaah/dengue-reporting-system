<?php
include 'dbcon.php';
session_start();

$div = $_SESSION['divisionId']; 
$userRole = $_SESSION['role'];

$query = "SELECT * FROM cases WHERE Verify_Status = 'Pending' AND Division_ID = '$div'";
$result = mysqli_query($conn, $query);

if (!$result) 
{
    echo "Query Error: " . mysqli_error($conn); // Display error if query fails
    exit();
}

$allCases = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Check if there are no pending cases
$noPendingCases = empty($allCases);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <link rel="stylesheet" href="viewallcasesstyle.css">
    <title>All Reported Cases</title>
</head>
<body>

<section id="allCasesSection" class="all-cases">
    <div class="navbar">
    <a href="javascript:history.back()"> Go Back</a>
    <a href="home.php">Home</a>
    <a href="view_all_cases.php"> View all Cases</a>
</div>
<br>
<h2>Pending Cases</h2>
<br>
    <table>
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Date</th>
                <th>Image</th>
                <th>Description</th>
                <th>Status</th>
                <th>Location</th>
                <th>No Of Deaths</th>
                <th>Comments</th>
                <th>GN Verification</th>
                <th>PHI Verification</th>
                <th>Resolving Officer</th>
                <th>Reporter ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($allCases as $case): // Loop through all cases and display them in table rows ?>
            <tr onclick="rowClick(this)">
                <td><?php echo !empty($case['Case_ID']) ? htmlspecialchars($case['Case_ID']) : '-'; ?></td>
                <td><?php echo !empty($case['Date_Time']) ? htmlspecialchars($case['Date_Time']) : '-'; ?></td>
                <td><?php if (!empty($case['Image_Src'])): ?>
                <img src="<?php echo htmlspecialchars($case['Image_Src']); ?>" alt="Case Image" style="width: 100px; height: auto;">
                <?php else: ?> No Image <?php endif; ?>
                </td>
                <td><?php echo !empty($case['Description']) ? htmlspecialchars($case['Description']) : '-'; ?></td>
                <td><?php echo !empty($case['Verify_Status']) ? htmlspecialchars($case['Verify_Status']) : '-'; ?></td>
                <td><?php echo !empty($case['Location']) ? htmlspecialchars($case['Location']) : '-'; ?></td>
                <td><?php echo !empty($case['No_Of_Deaths']) ? htmlspecialchars($case['No_Of_Deaths']) : '-'; ?></td>
                <td><?php echo !empty($case['Comments']) ? htmlspecialchars($case['Comments']) : '-'; ?></td>
                <td><?php echo !empty($case['VerifiedBy_GN']) ? htmlspecialchars($case['VerifiedBy_GN']) : 'Pending'; ?></td>
                <td><?php echo !empty($case['VerifiedBy_PHI']) ? htmlspecialchars($case['VerifiedBy_PHI']) : 'Pending'; ?></td>
                <td><?php echo !empty($case['ResolvedBy_Police']) ? htmlspecialchars($case['ResolvedBy_Police']) : '-'; ?></td>
                <td><?php echo !empty($case['User_ID']) ? htmlspecialchars($case['User_ID']) : '-'; ?></td>
                <td>
                    <button class="verify-case-btn" onclick="verifyCase(event)">Verify</button>
                    <button class="view-user-details-btn" onclick="viewUserDetails(event)">View User</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<form id="caseForm" method="post" action="" style="display:none;">
    <input type="hidden" name="selectedCaseID" id="selectedCaseID" value="">
</form>

</body>
</html>

<script type="text/javascript">
    var selectedCaseID = '';

    function rowClick(row) 
    {
        // Get the Case ID from the clicked row
        selectedCaseID = row.cells[0].innerText;
        document.getElementById('selectedCaseID').value = selectedCaseID;
    }

    function verifyCase(event) 
    {
        event.stopPropagation(); // Prevent the row click event from firing
        if (selectedCaseID) 
        {
            document.getElementById('caseForm').action = 'verify_case.php';
            document.getElementById('caseForm').submit();
        } else

        {
            alert('Please select a case first.');
        }
    }

    function viewUserDetails(event)
    {
        event.stopPropagation(); // Prevent the row click event from firing
        if (selectedCaseID) 
        {
            document.getElementById('caseForm').action = 'view_user_details.php';
            document.getElementById('caseForm').submit();
        }
        else
        {
            alert('Please select a case first.');
        }
    }
</script>