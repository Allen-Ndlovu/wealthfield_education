<?php
session_start(); 
include '../components/connect.php';
if (isset($_SESSION['message'])) {
   echo '
   <div class="message">
       <span>' . $_SESSION['message'] . '</span>
       <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   </div>
   ';
   // Clear the message after displaying
   unset($_SESSION['message']); // Clear the message to prevent it from showing again
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   if($select_tutor->rowCount() > 0){
     setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:dashboard.php');
   }else{
      $message[] = 'incorrect email or password!';
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

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

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>welcome back!</h3>
      <p>Email Address<span>*</span></p>
      <input type="email" name="email" placeholder="Enter your email address" maxlength="30" required class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
       <!-- Forgot password link -->
       <p class="link"><a href="tutor_forgot_password.php">Forgot Password?</a></p>
      
      <p class="link">Don't have an account? <a href="register.php">Register</a></p>
      <input type="submit" name="submit" value="login" class="btn">
   </form>

</section>

<!-- registe section ends -->

   
</body>
</html>