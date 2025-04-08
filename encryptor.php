<?php
define('AES_KEY', 'mysecretkey12345'); // Change to your own secure 16/24/32-char key

function encryptData($data) {
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-128-CBC', AES_KEY, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptData($encryptedData) {
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16);
    $ciphertext = substr($data, 16);
    return openssl_decrypt($ciphertext, 'AES-128-CBC', AES_KEY, OPENSSL_RAW_DATA, $iv);
}
?>
