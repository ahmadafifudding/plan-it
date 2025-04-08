<?php
require 'encryptor.php';

$conn = new mysqli("localhost", "root", "", "plan_it");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$dummyTransactions = [
  [3, 1, '100.00', 1, 'Online Transfer'],
  [4, 2, '85.50', 0, 'QR Code'],
  [5, 3, '120.00', 1, 'Online Transfer'],
  [6, 4, '99.99', 1, 'QR Code'],
  [3, 5, '70.00', 0, 'Online Transfer'],
  [4, 1, '150.00', 1, 'QR Code'],
  [5, 2, '95.00', 0, 'Online Transfer'],
  [6, 3, '110.50', 1, 'QR Code']
];

foreach ($dummyTransactions as $t) {
  [$user_id, $event_id, $amount, $status, $method] = $t;

  $encrypted_amount = encryptData($amount);
  $encrypted_method = encryptData($method);

  $stmt = $conn->prepare("INSERT INTO transaction (user_id, event_id, amount, payment_status, payment_method) 
                          VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issis", $user_id, $event_id, $encrypted_amount, $status, $encrypted_method);
  $stmt->execute();
}

echo "✅ AES-encrypted dummy transactions inserted successfully.";
$conn->close();
?>
