<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'boss') {
  echo "<script> alert('❌ Access denied. This page is only for the Founder (Boss).');
  window.location.href = 'home.php';
  </script>";
  exit();
}
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rent Fees Record | PLANIT</title>
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
    }

    .box {
      background-color: #333;
      color: white;
      padding: 30px;
      border-radius: 10px;
      width: 350px;
      height: 250px;
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

    .sidebar-right .account {
      text-align: center;
    }

    .sidebar-right .account i {
      font-size: 24px;
      margin-bottom: 5px;
    }

   
    .toolbar {
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
    }

    .toolbar i {
      background: #eee;
      padding: 10px;
      border-radius: 8px;
      cursor: pointer;
    }* Right Sidebar */
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
  <!-- Sidebar kiri -->
  <div class="sidebar-left">
    <img src="logo.png" alt="PLANIT Logo" style="width: 140px; margin-bottom: 30px;">
    <a href="home.php" class="nav-link">Home</a>
    <a href="event.php" class="nav-link">Event</a>
	<a href="financial.php" class="nav-link">Financial</a>
    <a href="rent_record.php" class="nav-link active">Rent Fees Record</a>
    <a href="news.php" class="nav-link">News</a>
  </div>

  <!-- Kandungan utama -->
  <div class="main-content">
    <div class="toolbar">
      <i class="fas fa-plus"></i>
      <i class="fas fa-lock"></i>
      <i class="fas fa-copy"></i>
      <i class="fas fa-trash-alt"></i>
      <i class="fas fa-ellipsis-h"></i>
    </div>

    <h2>Rent Fees Record</h2>
    <div class="box">
      Content for all users
    </div>
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
