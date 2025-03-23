<?php
include 'dbcon.php';
session_start();

if (isset($_SESSION['User_ID'])) 
{
    $userId = $_SESSION['User_ID']; 
    $userRole = $_SESSION['role'];  
    $name = $_SESSION['username'];  

    if ($userRole === "PHI") 
    {
        $roleMessage = "Welcome, PHI $name!";
    }
    elseif ($userRole === "GN") 
    {
        $roleMessage = "Welcome, GN $name!";
    }
    elseif ($userRole === "CP")
    {
        $roleMessage = "Welcome, Community Police $name!";
    }
    elseif ($userRole === "MOH")
    {
        $roleMessage = "Welcome, MOH $name!";
    }
    elseif ($userRole === "GU") 
    {
        $roleMessage = "Welcome, $name!";
    }
    else 
    {
        $roleMessage = "User not found!";
    }

    $query = "SELECT general_user.*, division.Division_Name 
              FROM general_user 
              JOIN division ON general_user.Division_ID = division.Division_ID 
              WHERE general_user.User_ID = '$userId'";
    $result = mysqli_query($conn, $query);

    if (!$result) 
    {
        echo "Query Error: " . mysqli_error($conn); 
        exit();
    }

    $userDetails = mysqli_fetch_array($result);
    $_SESSION['divisionId'] = $userDetails['Division_ID'];

    //fetching the Division_ID of the reporter for each case
    $caseQuery = "SELECT cases.*, general_user.Division_ID as Reporter_Division_ID 
                  FROM cases 
                  JOIN general_user ON cases.User_ID = general_user.User_ID 
                  WHERE cases.User_ID = '$userId'";
    $caseResult = mysqli_query($conn, $caseQuery);

    // Fetch all cases as an array
    $cases = mysqli_fetch_all($caseResult, MYSQLI_ASSOC);

    $caseCount = count($cases);
}
else
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">    
<link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <link rel="stylesheet" href="accountstyle.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <h2><?php echo $roleMessage; ?></h2>
                <?php if ($userDetails): ?>
                    <ul>
                        <li><strong>Username:</strong> <?php echo htmlspecialchars($userDetails['Username']); ?></li>
                        <li><strong>Contact:</strong> <?php echo htmlspecialchars($userDetails['Contact']); ?></li>
                        <li><strong>Address:</strong> <?php echo htmlspecialchars($userDetails['Address']); ?></li>
                        <li><strong>Division:</strong> <?php echo htmlspecialchars($userDetails['Division_Name']); ?></li>
                    </ul>
                <?php else: ?>
                    <p>No user details found.</p>
                <?php endif; ?>
            </div>

            <div class="features">
                <?php if ($userRole === "PHI" || $userRole === "GN" || $userRole === "MOH"): ?>
                    <button id="viewAllCasesBtn">View All Reported Cases</button>
                <?php endif; ?>
                <?php if ($userRole === "PHI" || $userRole === "GN"): ?>
                    <button id="viewPendingCasesBtn">View Pending Cases</button>
                <?php endif; ?>

                <?php if ($userRole === "PHI" || $userRole === "MOH"): ?>
                    <button onclick="window.location.href='announce_outbreak.php'">Announce Outbreak</button>
                    <button id="viewAndEditMapBtn" onclick="window.location.href = 'home.php#map'">View & Edit Map</button>
                <?php endif; ?>

                <?php if ($userRole === "MOH" || $userRole === "GN"): ?>
                    <button id="createReport">Generate Report</button>
                <?php endif; ?>

                <?php if ($userRole === "CP"): ?>
                    <button id="viewUsers">View Users</button>
                <?php endif; ?>

                <button id="reportaCase">Report a Case</button>
                <button id="logoutBtn">Logout</button>
                <button id="goBackBtn">Go Back</button>
            </div>
        </aside>

        <main class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
            </div>

            <section id="caseHistorySection" class="case-history">
                <h2>Cases Reported by <?php echo $name; ?></h2>
                <div class="cards">
                    <div class="card">
                        <h3>Total Cases Reported</h3>
                        <p><?php echo $caseCount; ?></p>
                    </div>

                    <div class="card">
                        <h3>Settings</h3>
                        <button id="settingsBtn">Open Settings</button>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Case ID</th>
                            <th>Date Reported</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cases as $case): ?>
                                <td><?php echo htmlspecialchars($case['Case_ID']); ?></td>
                                <td><?php echo htmlspecialchars($case['Date_Time']); ?></td>
                                <td><?php echo htmlspecialchars($case['Verify_Status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>

        document.getElementById("viewAllCasesBtn")?.addEventListener("click",()=> 
        {
            window.location.href = "view_all_cases.php"
        })

        document.getElementById("viewPendingCasesBtn")?.addEventListener("click", () => 
        {
            window.location.href = "view_pending_cases.php";
        });

        document.getElementById("createReport")?.addEventListener("click", () => 
        {
            window.location.href = "create_report.php";
        });

        document.getElementById("view-user-btn")?.addEventListener("click", () => 
        {
            window.location.href = "view_user_details.php";
        });

        document.getElementById("goBackBtn").addEventListener("click", () => 
        {
            window.location.href = "home.php";
        });

        document.getElementById("reportaCase").addEventListener("click", () => 
        {
            window.location.href = "beforereporting.php";
        });

        document.getElementById("logoutBtn").addEventListener("click", () => 
        {
            window.location.href = "logout.php";
        });

        document.getElementById("settingsBtn").addEventListener("click", () => 
        {
            window.location.href = "settings.php";
        });

        document.getElementById("viewUsers").addEventListener("click", () => 
        {
            window.location.href = "officerusers.php";
        });

    function navigateToMap() 
    {
        window.location.href = "home.php#map";
    }

</script>