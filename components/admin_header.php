<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>

    <style>
        
        .header .logo {
            display: flex;
            align-items: center;
            text-decoration: none; 
        }

       
        .logo-holder {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px; 
        }

        .logo-img {
            height: 40px; 
            width: auto;
            display: inline-block;
        }

        
        .logo-text {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff; 
        }
        
    </style>
</head>
<body>

<header class="header">
    <section class="flex">

    <a href="dashboard.php" class="logo flex">
            <div class="logo-holder">
                <img src="images/logo.png" alt="WealthField Logo" class="logo-img">
            </div>
            <span class="logo-text">WealthField Tutor</span>
        </a>

   

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
      
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span><?= $fetch_profile['profession']; ?></span>
         
         <div class="flex-btn">
            
            
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php
            }else{
         ?>
          <img src="images/pic-1.jpg" class="image" alt="">
         <h3>Welcome to WealthField</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span><?= $fetch_profile['profession']; ?></span>
         <a href="profile.php" class="btn">view profile</a>
         <?php
            }else{
         ?>
         <img src="images/pic-1.jpg" class="image" alt="">
         <h3>WealthField</h3>
          <div class="flex-btn" style="padding-top: .3rem;">
           
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="create_course.php"><i class="fa-solid fa-bars-staggered"></i><span>Courses</span></a>
      <a href="upload_quiz.php"><i class="fas fa-comment"></i><span>Quizzes</span></a>
      <a href="upload_exam.php"><i class="fas fa-graduation-cap"></i><span>Exams</span></a>
      <a href="view_attempts.php"><i class="fas fa-comment"></i><span>Attempts</span></a>
      <a href="comments.php"><i class="fas fa-comment"></i><span>Comments</span></a>

      

      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a>
   </nav>

</div>

<!-- side bar section ends -->