<?php
include '../components/connect.php';

if (isset($_POST['submit'])) {
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   // Check if the email exists in the `tutors` table
   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? LIMIT 1");
   $select_tutor->execute([$email]);

   if ($select_tutor->rowCount() > 0) {
      // Here, code to send reset link or instructions should be implemented
      $message[] = 'Password reset instructions have been sent to your email.';
   } else {
      $message[] = 'Email not found!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot Password - Tutors</title>

   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="padding-left: 0;">

<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message form">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">
   <form action="" method="post" class="login">
      <h3>Forgot Password</h3>
      <p>Enter your registered email to reset password</p>
      <input type="email" name="email" placeholder="Enter your email address" maxlength="30" required class="box">
      <input type="submit" name="submit" value="Send Reset Instructions" class="btn">
   </form>
</section>

</body>
</html>
