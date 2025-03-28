<?php 
    require('db_connect.php');  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap link-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
  rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
  crossorigin="anonymous">

  <!--icons-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <link rel="icon" href="src\icon.png">
  
  <title>Dropwise--Drop Your File</title>
  <style>
    body {
      overflow-x: hidden;
    }

    .navbar {
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    height: 5rem;
    z-index: 1000;
    }

    .sidebar {
      height: calc(100vh - 56px); 
      position:fixed;
      left: 0;
      width: 70px;
      background-color:white;
      padding-top: 15px; 
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
      z-index: 1;
      
    }

    
    .sidebar a {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      padding: 10px 15px;
      display: block;
      color:while;
      text-decoration: none;
    }

    .sidebar a:hover {
      background-color: #e2e6ea;
    }

    .content {
    margin-left: 80px; /* width of sidebar */
    padding: 20px;
    padding-top: 30px; /* top margin to clear navbar */
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="position:sticky; background-color:#3674B5; ">
    <a class="navbar-brand ms-3" href="#" style="font-family: 'Lucida Handwriting'; font-weight: 600; font-style: italic; font-size: 1.5rem; color:white;">
      DropWise
    </a>
  </nav>
      <!--Side bar-->
        <div class="sidebar">
          <a href="files.php" data-bs-toggle="tooltip" data-bs-placement="right" title="Files">
            <i class="bi bi-folder2-open ms-1 fs-3"></i>
          </a>
          <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-bs-placement="right" title="Upload">
            <i class="bi bi-cloud-arrow-up ms-1 fs-3"></i>
          </a>
          
        </div>
    <!--Side bar-->


  <!--Upload Your File Modal-->
  
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Upload Your File</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <form action="files.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Enter Full Name" required>
            </div>

            <div class="mb-3">
              <label for="file" class="form-label">Upload File</label>
              <input class="form-control" type="file" name="file" id="file" required>
            </div>

            <div class="mb-3">
              <label for="pwd" class="form-label">Password</label>
              <input type="password" id="pwd" name="pwd" class="form-control" required>
              
            </div>

            <div class="mb-3">
              <label for="expire_date" class="form-label">Valid Till</label>
              <input type="datetime-local" class="form-control" id="expire_date" name="expire_date" required>
              <small class="form-text">Must be a future date.</small>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Upload</button>
            </div>
          </form>

          <script>
              function validateForm() {
                  let password = document.getElementById("pwd").value;
                  let expireDate = new Date(document.getElementById("expire_date").value);
                  let currentDate = new Date();
                  
                  let passwordPattern = /^(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,20}$/;
                  
                  if (!passwordPattern.test(password)) {
                      alert("Password must be 8-20 characters long and include at least one special character.");
                      return false;
                  }
                  
                  if (expireDate <= currentDate) {
                      alert("Expiry date must be in the future.");
                      return false;
                  }
                  
                  return true;
              }
          </script>

          </div>
        </div>
      </div>
    </div>
  <!--upload Your File Moadal-->
  
  
  <!-- Bootstrap JS (required for tooltips) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Activate Bootstrap Tooltips -->
<script>
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>

</body>
</html>