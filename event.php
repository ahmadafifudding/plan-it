<?php
session_start();
if (!isset($_SESSION['role'])) {
  echo "❌ Access denied. Please log in.";
  exit();
}
$username = $_SESSION['username'];
?>

<html>
<head>
  <meta charset="UTF-8">
  <title>Event | PLANIT</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .wrapper {
      display: flex;
      height: 100vh;
    }

    .sidebar-left {
      background-color: #dcd2f9;
      width: 180px;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-start;
    }

    .sidebar-left h3 {
      margin-bottom: 30px;
    }

    .nav-link {
      margin: 10px 0;
      padding: 10px 15px;
      border-radius: 10px;
      background-color: transparent;
      color: black;
      text-decoration: none;
      font-weight: bold;
    }

    .nav-link.active,
    .nav-link:hover {
      background-color: #bda5dc;
      color: white;
    }

    .main-content {
      flex: 1;
      padding: 40px 20px;
      position: relative;
      overflow-y: auto;
    }
	
	.event-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .event-table th, .event-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .event-table th {
      background-color: #bda5dc;
      color: white;
    }

    .event-table tr:hover {
      background-color: #f1f1f1;
    }

    .create-button {
      margin-top: 20px;
      background-color: #bda5dc;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      cursor: pointer;
    }

    .create-button:hover {
      background-color: #9e8bc7;
    }

    .sidebar-right {
      background-color: #dcd2f9;
      width: 150px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
    }

    .account {
      text-align: center;
      cursor: pointer;
    }

    .account i {
      font-size: 24px;
      margin-bottom: 5px;
    }

    .setting-btn {
      background-color: #bda5dc;
      color: black;
      padding: 8px 12px;
      border-radius: 10px;
      text-decoration: none;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 10px;
    }

    .setting-btn:hover {
      background-color: #9e8bc7;
      color: white;
    }

    .logout-btn {
      background-color: #bda5dc;
      color: black;
      padding: 8px 12px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #9e8bc7;
      color: white;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fff;
      margin: 20% auto;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="wrapper">

  <div class="sidebar-left">
  <img src="logo.png" alt="PLANIT Logo" style="width: 140px; margin-bottom: 30px;">
    <a href="home.php" class="nav-link">Home</a>
    <a href="event.php" class="nav-link active">Event</a>
    <a href="financial.php" class="nav-link">Financial</a>
    <a href="rent_record.php" class="nav-link">Rent Fees Record</a>
    <a href="news.php" class="nav-link">News</a>
  </div>

  <div class="main-content">
    <h2>Event</h2>

    <?php
    $conn = new mysqli("d1kb8x1fu8rhcnej.cbetxkdyhwsb.us-east-1.rds.amazonaws.com", "bylsqji766ehgrks", "bcqsrbxha0fvrzyu", "uz0n9ksy9lf4al5t");

    if ($conn->connect_error) {
      echo "<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>";
    } else {
      $query = "SELECT event_name, event_date, event_location FROM event ORDER BY event_date ASC";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        echo "<table class='event-table'>
                <thead>
                  <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                  </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . htmlspecialchars($row['event_name']) . "</td>
                  <td>" . date("d M Y, h:i A", strtotime($row['event_date'])) . "</td>
                  <td>" . htmlspecialchars($row['event_location']) . "</td>
                </tr>";
        }
        echo "</tbody></table>";
      } else {
        echo "<p>No upcoming events found.</p>";
      }
      $conn->close();
    }
    ?>

    <button class="create-button">Create your new idea board</button>
  </div>

    <!-- Sidebar Right -->
  <div class="sidebar-right">
    <div class="account" onclick="openModal()">
      <i class="fas fa-user-circle"></i>
      <div>Account</div>
    </div>

    <div style="display: flex; flex-direction: column; align-items: center;">
      <a href="user_settings.php" class="setting-btn">
        <i class="fas fa-cog"></i> Setting
      </a>
      <form action="logout.php" method="post">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

<!-- Modal Popup -->
<div id="accountModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h3>Hello, <?php echo htmlspecialchars($username); ?> 👋</h3>
    <p>You are logged in as: <strong><?php echo $_SESSION['role']; ?></strong></p>
  </div>
</div>
</div>
<script>
  function openModal() {
    document.getElementById("accountModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("accountModal").style.display = "none";
  }

  window.onclick = function(event) {
    const modal = document.getElementById("accountModal");
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>
</body>
</html>
