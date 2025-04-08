<?php
session_start();
require 'encryptor.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  echo "<script> alert('❌ Access denied. This page is only for Admin.');
  window.location.href = 'home.php';
  </script>";
  exit();
}
$username = $_SESSION['username'];
?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Financial | PLANIT</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .wrapper {
      display: flex;
      min-height: 100vh;
      padding-right: 150px; /* make space for fixed right sidebar */
    }

    .sidebar-left {
      background-color: #dcd2f9;
      width: 180px;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
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

    .nav-link.active, .nav-link:hover {
      background-color: #bda5dc;
      color: white;
    }

    .main-content {
      flex: 1;
      padding: 40px 20px;
      overflow-x: auto;
    }

    .transaction-table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .transaction-table th, .transaction-table td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .transaction-table th {
      background-color: #bda5dc;
      color: white;
    }

    .transaction-table tr:hover {
      background-color: #f1f1f1;
    }

    /* Right Sidebar */
    .sidebar-right {
      background-color: #dcd2f9;
      width: 150px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: fixed;
      right: 0;
      top: 0;
      height: 100vh;
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
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

    /* Modal */
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
	.table-wrapper {
	margin-right: 50px; /* Add spacing from sidebar */
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
    <a href="financial.php" class="nav-link active">Financial</a>
    <a href="rent_record.php" class="nav-link">Rent Fees Record</a>
    <a href="news.php" class="nav-link">News</a>
  </div>

  <!-- Main content -->
  <div class="main-content">
    <h2 style="margin-bottom: 20px;">Transaction Records</h2>
	
	<div class="table-wrapper">
    <table class="transaction-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Event</th>
          <th>Amount (RM)</th>
          <th>Status</th>
          <th>Method</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $conn = new mysqli("localhost", "root", "", "plan_it");

        $query = "
          SELECT 
            t.transaction_id,
            u.username,
            e.event_name,
            t.amount,
            t.payment_status,
            t.payment_method
          FROM transaction t
          JOIN users u ON t.user_id = u.user_id
          JOIN event e ON t.event_id = e.event_id
          ORDER BY t.transaction_id ASC
        ";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>#Transaction" . str_pad($row['transaction_id'], 4, '0', STR_PAD_LEFT) . "</td>
              <td>" . htmlspecialchars($row['username']) . "</td>
              <td>" . htmlspecialchars($row['event_name']) . "</td>
              <td>" . decryptData($row['amount']) . "</td>
              <td style='color:" . ($row['payment_status'] == 1 ? "green" : "orange") . "'>" . ($row['payment_status'] == 1 ? "Completed" : "Pending") . "</td>
              <td>" . decryptData($row['payment_method']) . "</td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='6'>No transactions found.</td></tr>";
        }

        $conn->close();
        ?>
      </tbody>
    </table>
  </div>
  </div>

  <!-- Sidebar kanan -->
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

<!-- Modal popup -->
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
