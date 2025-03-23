<?php
include 'dbcon.php';
session_start();

$div = $_SESSION['divisionId']; 
$userRole = $_SESSION['role'];

$query = "SELECT * FROM cases WHERE Division_ID = '$div'";
$result = mysqli_query($conn, $query);

if (!$result) 
{
    echo "Query Error: " . mysqli_error($conn); 
    exit();
}

$allCases = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="viewallcasesstyle.css">
    <title>All Reported Cases</title>
</head>
<body>

<section id="allCasesSection" class="all-cases">
    <div class="navbar">
    <a href="javascript:history.back()"> Go Back</a>
    <a href="home.php">Home</a>
    <a href="view_pending_cases.php"> View Pending Cases</a>
</div>
<br>
<h2>All Reported Cases</h2>
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
                <?php else: ?>
                    No Image
                 <?php endif; ?>
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
                <?php if ($userRole === "PHI" || $userRole === "GN"): ?>
                    <button class="verify-case-btn" onclick="verifyCase(event)">Verify</button><br>
                <?php endif; ?>
                    <button class="view-user-details-btn" onclick="viewUserDetails(event)">View User</button>
                    <button class="add-comments-btn" onclick="addComments(event)">Comment</button>
                    <button class="resolve-case-btn" onclick="resolveCase(event)">Get Help</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<div class="officer-selection-card" id="officerCard">
    <h5>Select Officer</h5>
    <table class="table">
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Station</th>
                <th>Select</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $officerQuery = "SELECT u.Username, u.Contact, u.User_ID, cp.Station_Name
                            FROM community_police cp
                            JOIN general_user u ON cp.User_ID = u.User_ID";

            $officerResult = mysqli_query($conn, $officerQuery);

            while ($officer = mysqli_fetch_assoc($officerResult)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($officer['Username']) . '</td>';
                echo '<td>' . htmlspecialchars($officer['Contact']) . '</td>';                            
                echo '<td>' . htmlspecialchars($officer['Station_Name']) . '</td>';
                echo '<td><button class="btn btn-primary" onclick="selectOfficer(' . htmlspecialchars($officer['User_ID']) . ', \'' . htmlspecialchars($officer['Username']) . '\')">Select</button></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-secondary" onclick="closeOfficerCard()">Close</button>
</div>

<form id="caseForm" method="post" action="" style="display:none;">
    <input type="hidden" name="selectedCaseID" id="selectedCaseID" value="">
</form>

</body>
</html>




<script type="text/javascript">
    var selectedCaseID = null; // Ensure this variable holds the ID of the selected case

    function rowClick(row) 
    {
        selectedCaseID = row.cells[0].innerText;
        document.getElementById('selectedCaseID').value = selectedCaseID;
    }

    function verifyCase(event)
    {
        event.stopPropagation(); 
        if (selectedCaseID) 
        {
            document.getElementById('caseForm').action = 'verify_case.php';
            document.getElementById('caseForm').submit();
        }
        else
        {
            alert('Please select a case first.');
        }
    }

    function viewUserDetails(event) 
    {
        event.stopPropagation(); 
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

    function addComments(event) 
    {
        event.stopPropagation(); 
        if (selectedCaseID) {
            var comment = prompt("Enter your comment:");
            if (comment) {
                document.getElementById('caseForm').action = 'add_comment.php'; 
                var commentInput = document.createElement('input');
                commentInput.type = 'hidden';
                commentInput.name = 'comment';
                commentInput.value = comment;
                document.getElementById('caseForm').appendChild(commentInput);
                document.getElementById('caseForm').submit();
            }
        }
        else
        {
            alert('Please select a case first.');
        }
    }

    function resolveCase(event) 
    {
    event.stopPropagation(); 
    if (selectedCaseID) 
    {
        document.getElementById('officerCard').style.display = 'block'; // Set display to block to show
    } 
    else 
    {
        alert('Please select a case first.');
    }
}

function closeOfficerCard() 
{
    document.getElementById('officerCard').style.display = 'none'; // Set display to none to hide
}

    function selectOfficer(officerID, officerName) 
    {
        var form = document.getElementById('caseForm');
        var officerInput = document.createElement('input');
        officerInput.type = 'hidden';
        officerInput.name = 'officerID';
        officerInput.value = officerID;

        var caseInput = document.createElement('input');
        caseInput.type = 'hidden';
        caseInput.name = 'selectedCaseID';
        caseInput.value = selectedCaseID;

        var officerNameInput = document.createElement('input');
        officerNameInput.type = 'hidden';
        officerNameInput.name = 'officerName';
        officerNameInput.value = officerName;

        form.appendChild(officerInput);
        form.appendChild(caseInput);
        form.appendChild(officerNameInput);

        form.action = 'resolve_case.php';
        form.submit();
    }


</script>
