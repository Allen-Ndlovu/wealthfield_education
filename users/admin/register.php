<?php
include '../components/connect.php';

define('MAX_TUTORS', 5); 

if (isset($_POST['submit'])) {

    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $profession = $_POST['profession'];
    $profession = filter_var($profession, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    // Profile picture upload
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $image_rename = unique_id() . '.' . $image_ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $image_rename;

    // CV PDF upload
    $cv = $_FILES['cv']['name'];
    $cv = filter_var($cv, FILTER_SANITIZE_STRING);
    $cv_ext = pathinfo($cv, PATHINFO_EXTENSION);

    // Ensure file is PDF
    if ($cv_ext != 'pdf') {
        $message[] = 'Only PDF files are allowed for CV!';
    } else {
        $cv_rename = unique_id() . '.pdf';
        $cv_tmp_name = $_FILES['cv']['tmp_name'];
        $cv_folder = '../uploaded_files/' . $cv_rename;

        // Check if the email is already registered as a tutor
        $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
        $select_tutor->execute([$email]);

        // Check if the email is already registered as a student
        $select_student = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_student->execute([$email]);

       // Check if the selected profession is already registered
        $select_profession = $conn->prepare("SELECT * FROM `tutors` WHERE profession = ?");
        $select_profession->execute([$profession]);

        if ($select_profession->rowCount() > 0) {
        $message[] = 'A tutor with this profession is already enrolled!';
        } elseif ($select_tutor->rowCount() > 0) {
        $message[] = 'Email already taken!';
        } elseif ($select_student->rowCount() > 0) {
        $message[] = 'Email already registered for a student!';
        } else {
    // Check current number of registered tutors
        $count_tutors = $conn->query("SELECT COUNT(*) FROM `tutors`")->fetchColumn();

        if ($count_tutors >= MAX_TUTORS) {
        $message[] = 'Registration failed: Maximum Tutor capacity reached.';
        } else {
          if ($pass != $cpass) {
            $message[] = 'Passwords do not match!';
        } else {
            // Insert tutor data along with the PDF CV file path
            $insert_tutor = $conn->prepare("INSERT INTO `tutors` (id, name, profession, email, password, image, cv) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $image_rename, $cv_rename]);

            // Move uploaded files to designated folders
            move_uploaded_file($image_tmp_name, $image_folder);
            move_uploaded_file($cv_tmp_name, $cv_folder);

            $message[] = 'Conditionally registered! Final feedback after review will be sent via email.';
        }
    }
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
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   
   <script>
      function validateName(input) {
         const regex = /^[A-Za-z\s]+$/;
         const messageElement = document.getElementById("nameError");
         if (!regex.test(input.value)) {
            messageElement.style.display = "block";
         } else {
            messageElement.style.display = "none";
         }
      }
   </script>
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
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Register new</h3>
      <div class="flex">
         <div class="col">
            <p>Full Name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter your full name" maxlength="50" required class="box" oninput="validateName(this)">
            <p id="nameError" style="color: red; display: none;">Please enter letters only.</p>
            <p>Profession <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- Select your profession</option>
               <option value="Back-end developer">Back-end Developer</option>
               <option value="Front-End Developer">Front-End Developer</option>
               <option value="Software developer">Software Engineer</option>
               <option value="Software Engineer">Mobile App Developer</option>
            </select>
            <p>Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="30" required class="box">
            <p>CV (PDF) <span>*</span></p>
            <input type="file" name="cv" accept="application/pdf" required class="box">
         </div>
         <div class="col">
            <p>New password <span>*</span></p>
            <input type="password" name="pass" placeholder="Enter your new password" maxlength="20" required class="box">
            <p>Confirm new password <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" required class="box">
            <p>Profile picture <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">Already have an account? <a href="login.php">Login</a></p>
      <input type="submit" name="submit" value="register" class="btn">
   </form>
</section>
