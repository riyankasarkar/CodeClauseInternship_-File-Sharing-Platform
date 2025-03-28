<?php
    $serverName = 'localhost';
    $userName = 'root';
    $password = '';
    $dbName = 'file_sharing';

    //Create Connection
    $conn = new mysqli($serverName,$userName,$password,$dbName);
    if ($conn -> connect_error){
        die("Connection Failed".$conn->connect_error);
    }
?>