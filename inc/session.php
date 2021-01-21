<?php
    session_start(); 
    include 'db.php'; // SQL Server

    if (empty($_SESSION['username'])) {
        header('Location: login.php'); // Redirect if no session
        exit;
    }
    mysqli_close($connection);
?>