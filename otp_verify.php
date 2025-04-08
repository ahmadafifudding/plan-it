<?php
session_start();

$entered_otp = $_POST['otp'];

// OTP expiration check (10 minutes)
if (isset($_SESSION['otp'], $_SESSION['otp_created_at']) && time() - $_SESSION['otp_created_at'] <= 600) {
    if ($entered_otp == $_SESSION['otp']) {
        unset($_SESSION['otp'], $_SESSION['otp_created_at']);
        echo "<script>alert('✅ OTP verified successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('❌ Invalid OTP!'); window.location.href='otp.html';</script>";
    }
} else {
    echo "<script>alert('❌ OTP expired. Please register again.'); window.location.href='signup.html';</script>";
    session_unset();
}
?>
