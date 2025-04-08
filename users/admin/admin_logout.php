<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: home.php"); // Change 'login.php' to the correct path of your login file
exit();
?>
