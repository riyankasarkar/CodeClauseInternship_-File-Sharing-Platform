<?php
require('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileId = $_POST['file_id'];
    $enteredPassword = $_POST['password'];
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['file_id'])) {
    $fileId = $_GET['file_id'];
} else {
    die("<script>alert('Error: Invalid request.'); window.location.href='files.php';</script>");
}

// Fetch file details
$stmt = $conn->prepare("SELECT uf.password, fm.file_name, uf.expiry_date 
                        FROM upload_file uf 
                        JOIN file_metadata fm ON uf.id = fm.file_id 
                        WHERE uf.id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashedPassword, $fileName, $expiryDate);
    $stmt->fetch();

    // Check if file is expired
    if (strtotime($expiryDate) < time()) {
        die("<script>alert('This file has expired and cannot be accessed.'); window.location.href='files.php';</script>");
    }

    // Verify password
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !password_verify($enteredPassword, $hashedPassword)) {
        die("<script>alert('Incorrect password. Try again!'); window.history.back();</script>");
    }

    // Redirect to file
    $filePath = "uploads/" . $fileName;
    if (file_exists($filePath)) {
        header("Location: $filePath");
        exit();
    } else {
        die("<script>alert('Error: File not found.'); window.location.href='files.php';</script>");
    }
} else {
    die("<script>alert('File not found.'); window.location.href='files.php';</script>");
}

$stmt->close();
$conn->close();
?>