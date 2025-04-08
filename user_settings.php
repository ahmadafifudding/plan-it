<?php
session_start();
if (!isset($_SESSION['role'])) {
  echo "❌ Access denied. Please log in.";
  exit();
}
$user_id = $_SESSION['user_id']; // To filter per user (optional)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Settings | PLANIT</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body { margin: 0; font-family: Arial, sans-serif; }
    .wrapper { display: flex; height: 100vh; }

    .sidebar-left {
      background-color: #dcd2f9;
      width: 180px;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
    }

    .sidebar-left h3 { margin-bottom: 30px; }

    .nav-link {
      margin: 10px 0;
      padding: 10px 15px;
      border-radius: 10px;
      text-decoration: none;
      color: black;
      font-weight: bold;
    }

    .nav-link.active, .nav-link:hover {
      background-color: #bda5dc;
      color: white;
    }

    .main-content {
      flex: 1;
      padding: 40px 20px;
      overflow-y: auto;
    }

    .setting-box {
      background-color: #333;
      color: white;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      margin-bottom: 20px;
    }

    .setting-box h3 {
      margin-top: 0;
      color: #ffd;
    }

    .setting-box p {
      font-size: 14px;
      margin: 6px 0;
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

    .account { text-align: center; }
    .account i { font-size: 24px; margin-bottom: 5px; }
    .setting { font-size: 14px; }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Sidebar Left -->
  <div class="sidebar-left">
    <h3>‘PLANIT LOGO’</h3>
    <a href="home.php" class="nav-link">Home</a>
    <a href="event.php" class="nav-link">Event</a>
    <a href="financial.php" class="nav-link">Financial</a>
    <a href="news.php" class="nav-link">News</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2>User Settings</h2>

    <?php
    $conn = new mysqli("localhost", "root", "", "plan_it");

    if ($conn->connect_error) {
      echo "<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>";
    } else {
      $query = "SELECT setting_key, setting_value, created_at FROM user_setting WHERE user_id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "
            <div class='setting-box'>
              <h3>" . htmlspecialchars($row['setting_key']) . "</h3>
              <p><strong>Status:</strong> " . htmlspecialchars($row['setting_value']) . "</p>
              <p><strong>Created At:</strong> " . date("d M Y, h:i A", strtotime($row['created_at'])) . "</p>
            </div>
          ";
        }
      } else {
        echo "<p>No settings found for your account.</p>";
      }

      $conn->close();
    }
    ?>
  </div>

  <!-- Sidebar Right -->
  <div class="sidebar-right">
    <div class="account">
      <i class="fas fa-user-circle"></i>
      <div>Account</div>
    </div>
     <div style="display: flex; flex-direction: column; align-items: center;">
    <a href="user_settings.php" class="setting-btn">
      <i class="fas fa-cog"></i> Setting
    </a>
  </div>
</div>
</body>
</html>
