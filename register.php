<?php
include 'components/connect.php';

define('MAX_CAPACITY', 5); 

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Check the current number of registered students
$select_student_count = $conn->prepare("SELECT COUNT(*) FROM `users`");
$select_student_count->execute();
$current_count = $select_student_count->fetchColumn();

if($current_count >= MAX_CAPACITY){
   $message[] = 'Registration capacity reached. Please try again next year.';
} else if(isset($_POST['submit'])) {

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Handle Matric Certificate upload
   $matric = $_FILES['matric']['name'];
   $matric = filter_var($matric, FILTER_SANITIZE_STRING);
   $matric_ext = pathinfo($matric, PATHINFO_EXTENSION);
   $matric_rename = unique_id().'_matric.'.$matric_ext;
   $matric_tmp_name = $_FILES['matric']['tmp_name'];
   $matric_folder = 'uploaded_files/'.$matric_rename;

   // Handle Bank Statement upload
   $bank_statement = $_FILES['bank_statement']['name'];
   $bank_statement = filter_var($bank_statement, FILTER_SANITIZE_STRING);
   $bank_ext = pathinfo($bank_statement, PATHINFO_EXTENSION);
   $bank_rename = unique_id().'_bank.'.$bank_ext;
   $bank_tmp_name = $_FILES['bank_statement']['tmp_name'];
   $bank_folder = 'uploaded_files/'.$bank_rename;

   // Check if the email is already registered in the users table
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);

   // Check if the email is already registered in the tutors table
   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);

   if($select_user->rowCount() > 0){
      $message[] = 'Email already registered!';
   } elseif($select_tutor->rowCount() > 0) {
      $message[] = 'Email already registered for a tutor!';
   } else {
      if($pass != $cpass){
         $message[] = 'Password does not match!';
      } else {
         // Insert the user and files
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, matric_certificate, bank_statement) VALUES(?,?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $matric_rename, $bank_rename]);

         // Move the uploaded files
         move_uploaded_file($matric_tmp_name, $matric_folder);
         move_uploaded_file($bank_tmp_name, $bank_folder);

         $message[] = 'Conditionally registered!. Final feedback after review will be sent via email.';
      }
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/header.php'; ?>

<section class="form-container">
   <?php if($current_count >= MAX_CAPACITY): ?>
      <p class="capacity-message">Registration is closed as we have reached full capacity. Please try again next year.</p>
   <?php else: ?>
      <form class="register" action="" method="post" enctype="multipart/form-data">
         <h3>Create an account</h3>
         <div class="flex">
            <div class="col">
            <p>Full Name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter your full name" maxlength="50" required class="box" oninput="validateName(this)">
            <span id="name-error" style="color: red; display: none;">Please enter letters only.</span>

               <p>Email <span>*</span></p>
               <input type="email" name="email" placeholder="Enter your email" maxlength="30" required class="box">
            </div>
            <div class="col">
               <p>Password <span>*</span></p>
               <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
               <p>Confirm Password <span>*</span></p>
               <input type="password" name="cpass" placeholder="Confirm your password" maxlength="20" required class="box">
            </div>
         </div>
         <p>Upload Matric Certificate <span>*</span></p>
         <input type="file" name="matric" accept=".pdf" required class="box">
         <p>Upload Bank Statement <span>*</span></p>
         <input type="file" name="bank_statement" accept=".pdf" required class="box">
           <p>select pic <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
         <input type="submit" name="submit" value="Register Now" class="btn">
      </form>
   <?php endif; ?>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
