<?php
session_start();

// Destroy the session
if (session_destroy()) {
    // Redirect to login page
    header("Location: login_admin.php");
    exit();
}
?>
