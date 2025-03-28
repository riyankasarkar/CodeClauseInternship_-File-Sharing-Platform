<?php
require('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['name'];
    $password = $_POST['pwd'];
    $expiryDate = $_POST['expire_date'];
    $uploadDate = date('Y-m-d H:i:s');
    
    // File upload 
    $uploadDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileSize = $_FILES["file"]["size"];
    $targetFilePath = $uploadDir . $fileName;

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];

    if (!in_array($fileType, $allowedTypes)) {
        die("<script>alert('Invalid file type! Allowed types: JPG, PNG, PDF, DOCX.'); window.history.back();</script>");
    }

    // Move uploaded file to uploads directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        
        // Hash password before storing for security purpose
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // store to data base
        $stmt = $conn->prepare("INSERT INTO upload_file (full_name, password, expiry_date, upload_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullName, $hashedPassword, $expiryDate, $uploadDate);
        if ($stmt->execute()) {
            $fileId = $stmt->insert_id;
            
            // share link
            $shareLink = "access_file.php?file_id=" . $fileId;

            // Insert into `file_metadata` table
            $stmt = $conn->prepare("INSERT INTO file_metadata (file_id, file_name, file_type, file_size, share_link) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $fileId, $fileName, $fileType, $fileSize, $shareLink);
            $stmt->execute();

            echo "<script>alert('File uploaded successfully!'); window.location.href='files.php';</script>";
        } else {
            echo "<script>alert('Database error: Unable to upload file.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('File upload failed!'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="src/icon.png">
    <title>Dropwise - Uploaded Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.9s ease-in-out;
        }

        .card:hover {
          
            border-color: #3674B5;
        }
        .card-body{
            height: 300px;
        }

    </style>
</head>

<body>

<?php require("index.php"); ?>

<div class="content">
    <h2 style="font-family: 'poppins'; font-weight: 500; font-size: 1.5rem; color:black">Uploaded Files</h2>
</div>

<div class="container">
    <div class="row">
        <?php
        // Fetch uploaded files
        $sql = "SELECT uf.id, uf.full_name, uf.upload_date, uf.expiry_date, 
                       fm.file_name, fm.file_type, fm.share_link 
                FROM upload_file uf 
                JOIN file_metadata fm ON uf.id = fm.file_id 
                ORDER BY uf.upload_date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fileId = $row['id'];
                $fileName = htmlspecialchars($row['file_name']);
                $uploader = htmlspecialchars($row['full_name']);
                $uploadDate = $row['upload_date'];
                $expiryDate = $row['expiry_date'];
                $shareLink = "access_file.php?file_id=" . $fileId;
                $fileType = $row['file_type'];

                // Determine file icon
                $icon = "src/default.png";
                if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
                    $icon = "src/img.png";
                } elseif ($fileType === 'pdf') {
                    $icon = "src/pdf.png";
                } elseif ($fileType === 'docx') {
                    $icon = "src/doc.png";
                }

                // Check if file is expired
                $isExpired = (strtotime($expiryDate) < time());
                $status = $isExpired ? "<span class='text-danger'>Expired</span>" : "Valid till: $expiryDate";

                echo "<div class='col-md-4'>
                        <div class='card border-2 shadow-sm mb-3 ms-3'>
                            <div class='row g-0'>
                                <div class='col-md-4 text-center'>
                                    <img class='img-fluid p-3' src='$icon' alt='File Icon' style='height: 100px;'>
                                </div>
                                <div class='col-md-8'>
                                    <div class='card-body'>
                                        <p class='card-text'>
                                            <strong>Name:</strong> $fileName<br>
                                            <strong>Uploader:</strong> $uploader<br>
                                            <strong>Uploaded on:</strong> $uploadDate
                                        </p>
                                        <h6 class='text-muted'>$status</h6>";
                if (!$isExpired) {
                    echo "<button type='button' class='btn btn-primary mt-2' data-bs-toggle='modal' data-bs-target='#passwordModal$fileId'>Access</button>
                          <button type='button' class='btn btn-secondary mt-2' data-bs-toggle='modal' data-bs-target='#shareLinkModal$fileId'>Share</button>";
                }
                echo "        </div>
                                </div>
                            </div>
                        </div>
                    </div>";

                // Password Modal
                if (!$isExpired) {
                    echo "<div class='modal fade' id='passwordModal$fileId' tabindex='-1'>
                            <div class='modal-dialog modal-sm'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'>Enter Password</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <form action='access_file.php' method='POST'>
                                            <input type='hidden' name='file_id' value='$fileId'>
                                            <input type='password' name='password' class='form-control' placeholder='Enter Password' required>
                                            <button type='submit' class='btn btn-primary mt-3'>Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>";

                    // Share Link Modal
                    echo "<div class='modal fade' id='shareLinkModal$fileId' tabindex='-1'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'>Share Link</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <p>Shareable Link:</p>
                                        <input type='text' class='form-control' value='$shareLink' readonly>
                                        <button type='button' class='btn btn-primary mt-3' onclick='copyToClipboard(this)'>Copy Link</button>
                                    </div>
                                </div>
                            </div>
                        </div>";
                }
            }
        } else {
            echo "<p>No files uploaded yet.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>

<script>
function copyToClipboard(button) {
    var input = button.previousElementSibling;
    input.select();
    navigator.clipboard.writeText(input.value);
    alert('Link copied to clipboard!');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>