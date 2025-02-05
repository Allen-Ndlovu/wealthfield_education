<?php
include 'components/connect.php'; // Connect to the database

// Admin credentials
$admin_email = 'wealthfielda@gmail.com'; // Example admin email
$admin_password = 'wealthadmin2024!'; // Example password

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement
$insert_admin = $conn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
$insert_admin->execute([$admin_email, $hashed_password]);

echo "Admin user added successfully!";
?>
