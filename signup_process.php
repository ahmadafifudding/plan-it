<?php
session_start();
require 'otp_mailer.php';

$host = "d1kb8x1fu8rhcnej.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$dbname = "uz0n9ksy9lf4al5t";
$username = "bylsqji766ehgrks";
$password = "bcqsrbxha0fvrzyu";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form values
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$pass = $_POST['password'];
$confirm_pass = $_POST['confirm_password'];
$role = 'employee'; // Default role set here

// Validate password match
if ($pass !== $confirm_pass) {
  echo "<script> alert('❌ Passwords do not match.');
  window.location.href = 'signup.html';
  </script>";
  exit();
}
  

// Check if username or email already exists
$check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$check->bind_param("ss", $username, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  echo "<script> alert('❌ Username or email already exists.');
  window.location.href = 'signup.html';
  </script>";
  exit();
}

// Hash and insert
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
$stmt->execute();

$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['email'] = $email;
$_SESSION['otp_created_at'] = time();

sendOTPEmail($email, $otp);

header("Location: otp.html");
exit();

$conn->close();
?>
