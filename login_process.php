<?php
session_start();

// DB connection
$host = "d1kb8x1fu8rhcnej.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$dbname = "uz0n9ksy9lf4al5t";
$username = "bylsqji766ehgrks";
$password = "bcqsrbxha0fvrzyu";

$conn = new mysqli($host, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get email & password from POST
$email = trim($_POST['email']);
$password = $_POST['password'];

// Query user by email
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  // Verify hashed password
  if (password_verify($password, $user['password'])) {
    // Login success - set session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Redirect to homepage
    header("Location: home.php");
    exit();
  } else {
    echo "<script> alert('❌ Incorrect password.');
	window.location.href = 'login.php';
	</script>";
  }
} else {
  echo "<script> alert('❌ No user found with that email.');
  window.location.href = 'login.php';
  </script>";
}

$conn->close();
?>
