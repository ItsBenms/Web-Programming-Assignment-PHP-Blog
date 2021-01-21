<?php
    session_start();
    
    if(session_destroy()) { // Destroys session if exists
        header("Location: login.php"); // Redirect
    }
?>