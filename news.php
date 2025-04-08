<?php
session_start();
if (!isset($_SESSION['role'])) {
  echo "<script>alert('❌ Access denied. Please log in.'); window.location.href='login.php';</script>";
  exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>News | PLANIT</title>
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
      overflow-y: auto;
    }

    .news-box {
      background-color: #333;
      color: white;
      padding: 20px;
      border-radius: 10px;
      width: 450px;
      margin-bottom: 20px;
    }

    .news-box h3 {
      margin-top: 0;
      color: #ffd;
    }

    .news-box p {
      font-size: 14px;
      margin: 6px 0;
    }

    .create-button {
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

    .account { text-align: center; cursor: pointer; }
    .account i { font-size: 24px; margin-bottom: 5px; }

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

  <!-- Sidebar kiri -->
  <div class="sidebar-left">
    <img src="logo.png" alt="PLANIT Logo" style="width: 140px; margin-bottom: 30px;">
    <a href="home.php" class="nav-link">Home</a>
    <a href="event.php" class="nav-link">Event</a>
    <a href="financial.php" class="nav-link">Financial</a>
    <a href="rent_record.php" class="nav-link">Rent Fees Record</a>
    <a href="news.php" class="nav-link active">News</a>
  </div>

  <!-- Main content -->
  <div class="main-content">
    <h2>News</h2>

    <?php
    $conn = new mysqli("localhost", "root", "", "plan_it");
    if ($conn->connect_error) {
      echo "<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>";
    } else {
      $query = "SELECT title, content, created_at FROM news ORDER BY created_at DESC";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "
            <div class='news-box'>
              <h3>" . htmlspecialchars($row['title']) . "</h3>
              <p>" . nl2br(htmlspecialchars($row['content'])) . "</p>
              <p><small>Posted on " . date("d M Y, h:i A", strtotime($row['created_at'])) . "</small></p>
            </div>
          ";
        }
      } else {
        echo "<p>No news available.</p>";
      }
      $conn->close();
    }
    ?>

    <button class="create-button">Create News</button>
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
</div>

<!-- Modal -->
<div id="accountModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h3>Hello, <?php echo htmlspecialchars($username); ?> 👋</h3>
    <p>You are logged in as: <strong><?php echo $_SESSION['role']; ?></strong></p>
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
