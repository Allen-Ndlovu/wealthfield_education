<?php
session_start();
include 'components/connect.php';

if (isset($_POST['submit'])) {
    $admin_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $admin_password = $_POST['pass']; // Use 'pass' since that's the name in the form

    // Prepare the SQL statement to fetch the admin record
    $select_admin = $conn->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
    $select_admin->execute([$admin_email]);
    $admin = $select_admin->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if ($admin && password_verify($admin_password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true; // Set session variable
        header('Location: admin_panel.php'); // Redirect to admin panel
        exit();
    } else {
        $message[] = 'Invalid credentials!'; // Message for invalid login
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<link rel="stylesheet" href="css/style.css">

</head>
<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Login(Admin)</h3>
      <p>Email Address<span>*</span></p>
      <input type="email" name="email" placeholder="Enter your email address" maxlength="30" required class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
      <input type="submit" name="submit" value="login" class="btn">
   </form>

</section>












<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
   
</body>
</html>