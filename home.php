<?php
include 'dbcon.php';
session_start();

$role = "";
$divisionId = null; 

if (isset($_SESSION['role'])) 
{
    $role = $_SESSION['role'];
    $divisionId = isset($_SESSION['divisionId']) ? $_SESSION['divisionId'] : null; // Check if divisionId is set
}

$notificationCount = 0;
$notifications = "";

// Fetch outbreak notifications for all users in the division
if ($divisionId !== null) 
{
    $outbreakQuery = "SELECT * FROM notification 
                      WHERE Case_ID = '$divisionId' AND Noti_Status = 'Unread'
                      ORDER BY Date_Time DESC";
    $outbreakResult = mysqli_query($conn, $outbreakQuery);

    while ($row = mysqli_fetch_assoc($outbreakResult)) 
    {
        $notificationCount++;
        $notifications .= "<li>Outbreak Alert: " . $row['Message'] . " - " . $row['Date_Time'] . "</li>";
    }
}

// Fetch case notifications only for PHI, GN, and MOH
if ($role === "PHI" || $role === "GN" || $role === "MOH") {  
    $caseQuery = "SELECT * FROM notification n
                  JOIN cases c ON n.Case_ID = c.Case_ID
                  WHERE c.Division_ID = '$divisionId' 
                  ORDER BY n.Date_Time DESC";
    $caseResult = mysqli_query($conn, $caseQuery);

    while ($row = mysqli_fetch_assoc($caseResult)) {
        if ($row['Noti_Status'] == 'Unread') {
            $notificationCount++;
        }

        $notifications .= "<li>Case Alert: " . $row['Message'] . " - " . $row['Date_Time'] . "</li>";
    }
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="homestyle.css">
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <link rel="icon" type="image/png" href="Images/denguardlogo.png">
    <title>DenGuard</title>
</head>
<body>
    <header>
    <div class="logo">
        <img src="Images/denguardlogo.png" alt="DenGuard Logo">
        <span>DenGuard</span>
    </div>
    <nav>
        <a href="#" class="item">Home</a>
        <a href="#map-updates" class="item">Map & Updates</a>
        <div class="item">
        <a href="#map-updates">News & Resources</a>
        <div class="dropdown">
          <div>
            <a href="#article">Articles</a>
            <a href="#ytvid">Videos</a>
            <a href="#news">Latest News</a>
          </div>
        </div>
      </div>
      </nav>
      <div class="icons">
        <div class="noti-container">
            <a href="#" id="noti-icon">
                <i onclick="updatenotif()" class='bx bx-bell'></i>
                <?php 

                if ($notificationCount > 0) 
                {
                 ?> <span class="noti-badge"><?php echo $notificationCount; ?> </span> <?php 
                }

                ?>
            </a>
            <div class="noti-dropdown" id="noti-dropdown">
                <ul id="noti-list">
                    <?php echo $notifications ? $notifications : '<li>No new notifications</li>'; ?>
                </ul>
            </div>
        </div>
        <a href="#"><i class='bx bx-user-circle' onclick="toggledash()"></i></a>
    </div>
    <div id="dashboard">
        <a href="account.php">My Account</a>
        <a href="settings.php">Settings</a>
        <a href="privacypolicy.html">Privacy Policy</a>
        <a href="aboutus.html">About Us</a>
         <?php if ($role): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
            <?php endif; ?>
        
    </div>
</header>

<script>

    function updatenotif() 
    {
        window.location.href = "updatenoti.php";
    }

    document.getElementById("noti-icon").addEventListener("click", function(event) 
    {
            event.preventDefault(); // Prevent default anchor behavior
            var dropdown = document.getElementById("noti-dropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function(event) 
    {
        if (!event.target.closest(".noti-container")) 
        {
                document.getElementById("noti-dropdown").style.display = "none";
        }
    });

function toggledash() 
{
    const dashboard = document.getElementById("dashboard"); 
    dashboard.classList.toggle("show"); 

    if (dashboard.classList.contains("show")) 
    {
        document.addEventListener("click", handleClickOutside); // Enable 
    }
    else
    {
        document.removeEventListener("click", handleClickOutside); // Disable 
    }
}

// Function to handle clicks outside the dashboard
function handleClickOutside(event) 
{
    const dashboard = document.getElementById("dashboard"); 
    const userIcon = document.querySelector('.bx-user-circle');

    if (!dashboard.contains(event.target) && event.target !== userIcon) 
    {
        dashboard.classList.remove("show"); // Hide the dashboard
        document.removeEventListener("click", handleClickOutside); 
    }
}

    document.addEventListener("DOMContentLoaded", function() 
    {
        document.querySelector(".hero").classList.add("loaded");
    });

</script>

    <section class="hero" id="heroo">
        <h1>Together, let's build a dengue-free environment.</h1>
        <p>Welcome to DenGuard – your platform for dengue prevention and awareness. Our mission is to create a cleaner, healthier environment by identifying and reporting mosquito breeding sites to reduce the spread of dengue. By encouraging community involvement and awareness, DenGuard aims to lower the risk of outbreaks and promote preventive measures.</p>
        <a href="beforereporting.php" class="btn">Report Now</a>
        <br><br>
    </section>

    <section class="cards">
        <div class="card">
            <img src="Images/dirtbg.jpg" alt="Cleaning Initiative">
            <div class="content">
                <h3>Prevention</h3>
                <p>Dengue prevention involves eliminating mosquito breeding sites, using repellents, wearing protective clothing, and sleeping under mosquito nets.</p>
                <a href="dengueinfo.html" class="btn">View all</a>
            </div>
        </div>
        <div class="card">
            <img src="Images/stagnantboat.jpg" alt="What is Dengue?">
            <div class="content">
                <h3>What is Dengue?</h3>
                <p>Dengue is a viral infection transmitted by mosquitoes, primarily Aedes mosquitoes, that can cause severe illness and complications.</p>
                <a href="dengueinfo.html" class="btn">View all</a>
            </div>
        </div>
        <div class="card">
            <img src="Images/stagnantwater.jpg" alt="Symptoms of Dengue">
            <div class="content">
                <h3>Symptoms</h3>
                <p>Dengue symptoms are signs of infection caused by the dengue virus, including fever, headache, and joint pain.</p><br>
                <a href="dengueinfo.html" class="btn">View all</a>
            </div>
        </div>
    </section>

<section class="report-section">
    <div class="report-background" style="background-image: url('Images/reportbgn.png');">
        <div class="report-overlay">
            <div class="report-content">
                <h3>Why Report?</h3>
                <p>By identifying and addressing these hotspots early, we can prevent the spread of dengue fever and protect vulnerable individuals, especially children and the elderly. Your report helps authorities take timely action, reducing the risk of outbreaks and ensuring a safer, healthier environment for everyone. Together, we can fight dengue and make a difference!</p>
                <a href="beforereporting.php" class="report-btn">Report Now</a>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() 
    {
        window.addEventListener('scroll', function() 
        {
            const reportSection = document.querySelector(".report-section");
            const position = reportSection.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (position < windowHeight) 
            {
                reportSection.classList.add("loaded");
            }
        });
    });
</script>

  <section class="map" id="map-updates">
    <div class="container">
        <div class="map-section">
            <h3>Map of Dengue-Affected Areas in Sri Lanka</h3>
            <div id="map" class="map" style="height: 500px; width:700px; margin: 10px 0;">
            <script>
            var map = L.map('map').setView([7.8731, 80.7718], 7); // Coordinates for Sri Lanka

            // Add OpenStreetMap tiles as the map's base layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
            {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Function to add a pulsating marker
            function addPulsatingMarker(latlng) 
            {
                var circle = L.circleMarker(latlng, 
                {
                    radius: 8, // Size of the dot
                    fillColor: "#2c5530", // Green color
                    color: "#2c5530", // Border color
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.7,
                    className: 'pulsating-marker' // Custom class for animation
                }).addTo(map);
            }

            // Add marker on click (only for PHI and MOH)
            <?php if ($role === "PHI" || $role === "MOH"): ?>
            map.on('click', function (e) {
                addPulsatingMarker(e.latlng);
                saveMarkerToDatabase(e.latlng);
            });

            function saveMarkerToDatabase(latlng) 
            {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_marker.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () 
                {
                    if (xhr.readyState === 4 && xhr.status === 200) 
                    {
                        console.log("Marker saved successfully!");
                    }
                };
                xhr.send("lat=" + latlng.lat + "&lng=" + latlng.lng);
            }
            <?php endif; ?>

            </script>
<?php include 'load_markers.php'; ?>
    </div>
  </div>
  <div class="center-container">
    <h3> Dengue Outbreaks: a closer look</h3>
    <img id="imgchart" src="Images/DengueComparison.png" alt="Dengue Comparison Chart">
</div>
    </section>

<section class="resources" id="resourcess">
    <div class="containerr">
        <h2>Resources on Dengue</h2>
        <div class="grid-container">
            <div class="resource-card" style="background-image: url('Images/articles.jpg');">
                <div class="overlay">
                    <h3 id="article">Articles</h3>
                    <ul>
                        <li><a href="https://www.who.int/news-room/fact-sheets/detail/dengue-and-severe-dengue">Understanding Dengue Fever</a></li>
                        <li><a href="https://mda.maryland.gov/plants-pests/pages/tips_rid_your_community_mosquito_breeding_sites.aspx">Preventing Mosquito Breeding</a></li>
                        <li><a href="https://my.clevelandclinic.org/health/diseases/17753-dengue-fever">Dengue Symptoms and Treatment</a></li>
                    </ul>
                </div>
            </div>
            <div class="resource-card" style="background-image: url('Images/youtube.jpg');">
                <div class="overlay">
                    <h3 id="ytvid">YouTube Videos</h3>
                    <ul>
                        <li><a href="https://youtu.be/1K3zLLhSknI?si=Jmco_tOoSzDzjQuG">What is Dengue?</a></li>
                        <li><a href="https://youtu.be/ZKPCUJFbEuI?si=QU3952GG45lie6lc">Dengue Prevention Tips</a></li>
                        <li><a href="https://youtu.be/P-_hqG9HKj8?si=CaCyzBoYCU1NnKtL">Recognizing Symptoms</a></li>
                    </ul>
                </div>
            </div>
            <div class="resource-card" style="background-image: url('Images/news.jpg');">
                <div class="overlay">
                    <h3 id="news">Latest News</h3>
                    <ul>
                        <li><a href="news1.html">Dengue Outbreak Update: What You Need to Know</a></li>
                        <li><a href="news2.html">New Research on Dengue Treatment</a></li>
                        <li><a href="news3.html">Community Efforts to Combat Dengue</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
    
<footer class="custom-footer">
    <div class="footer-content">
        <p>&copy; 2025 DenGuard. All rights reserved.</p>
        <p>This platform is developed as part of a diploma final project for educational purposes.</p>
        <p>For inquiries or more details, visit our <a href="aboutus.html" class="footer-link">About Us</a> page.</p>
    </div>
</footer>


<script>
    document.addEventListener("DOMContentLoaded", function() 
    {
        const footer = document.querySelector(".custom-footer");

        function checkFooterVisibility() 
        {
            const rect = footer.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;

            if (rect.top <= windowHeight && rect.bottom >= 0)
            {
                footer.classList.add("show-footer");
            }
            else 
            {
                footer.classList.remove("show-footer");
            }
        }

        window.addEventListener('scroll', checkFooterVisibility);
        window.addEventListener('resize', checkFooterVisibility);

        // Initial check in case the footer is in view on page load
        checkFooterVisibility();
    });
</script>
</body>
</html>
