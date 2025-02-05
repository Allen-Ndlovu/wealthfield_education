<?php
include 'components/connect.php';

if (isset($_POST['submit'])) {
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   // Check if the email exists in the `users` table
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
   $select_user->execute([$email]);

   if ($select_user->rowCount() > 0) {
      // Code to send reset link or instructions
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
   <title>Forgot Password</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<!-- custom css file link  -->
<link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/header.php'; ?>

<section class="form-container">
   <form action="" method="post" class="login">
      <h3>Forgot Password</h3>
      <p>Enter your registered email to reset password</p>
      <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
      <input type="submit" name="submit" value="send reset instructions" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
