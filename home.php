<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM likes WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM comments WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM bookmark WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .box-container {
   border: 1px solid #ddd;
   margin-bottom: 20px;
   padding: 15px;
   transition: background-color 0.3s ease;
}

.box h3 {
   font-size: 1.5em;
   margin: 0;
   cursor: pointer;
   color: #333;
}

/* Content hidden initially */
.box-content {
   display: none;
   padding-left: 15px;
   margin-top: 10px;
   color: #555;
}

.box-container.active {
   background-color: #f9f9f9;
}

.box-container.active .box-content {
   display: block;
}

</style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

<h1 class="heading">Home</h1>

   <div class="box-container">

      <?php
         ($user_id != '')
      ?>

<div class="box-container">
      
      <div class="box">
         <h3>Free Courses Offered</h3>
         <div class="box-content">
            <div class="flex">
            <a href="#"><i class="fab fa-html5"></i><span>HTML</span></a>
            <a href="#"><i class="fab fa-css3"></i><span>CSS</span></a>
            <a href="#"><i class="fab fa-js"></i><span>Javascript</span></a>
            <a href="#"><i class="fab fa-react"></i><span>React Native</span></a>
            <a href="#"><i class="fab fa-php"></i><span>PHP</span></a>
            <a href="#"><i class="fas fa-database"></i><span>MySQL</span></a>
            <a href="#"><i class="fas fa-coffee"></i><span>Java</span></a>
            <a href="#"><i class="fas fa-code"></i><span>Python</span></a>
            <a href="#"><i class="fas fa-code"></i><span>C#</span></a>
            <a href="#"><i class="fas fa-code"></i><span>C++</span></a>
            <a href="#"><i class="fas fa-code"></i><span>Flutter</span></a>


         </div>
      </div>

      </div>
      </div>
  

    <div class="box-container">
      <div class="box">
         <h3>Free Badges and Certification</h3>
         <div class="box-content">
            <div class="flex">
      
        <a href="https://www.freecodecamp.org/" target="_blank">
            <i class="fas fa-laptop-code"></i>
            <span>FreeCodeCamp</span>
        </a>

        <a href="https://skillsbuild.org/" target="_blank">
            <i class="fas fa-certificate"></i>
            <span>IBM SkillsBuild</span>
        </a>

        <a href="https://aws.amazon.com/training/awsacademy/" target="_blank">
            <i class="fas fa-school"></i>
            <span>AWS Academy</span>
        </a>

        <a href="https://www.cisco.com/c/m/en_sg/partners/cisco-networking-academy/index.html" target="_blank">
            <i class="fas fa-graduation-cap"></i>
            <span>Cisco</span>
        </a>

        <a href="https://www.oracle.com/za/education/skills-development/learning-explorer/#oci" target="_blank">
            <i class="fas fa-code"></i>
            <span>Oracle</span>
        </a>

    </div>
</div>
</div>
</div>

<div class="box-container">
     <div class="box">
         <h3>Career Opportunities</h3>
         <div class="box-content">
            <div class="flex">
            <a href="#"><i class="fas fa-code"></i><span>Web Designer</span></a>
            <a href="#"><i class="fas fa-server"></i><span>Back-end developer</span></a>
            <a href="#"><i class="fas fa-laptop"></i><span>Front-end developer</span></a>
            <a href="#"><i class="fas fa-layer-group"></i><span>Full Stack developer</span></a>
            <a href="#"><i class="fas fa-laptop-code"></i><span>Software developer</span></a>
            <a href="#"><i class="fas fa-laptop-code"></i><span>Mobile App Developer</span></a>
            <a href="#"><i class="fas fa-laptop-code"></i><span>Software Engineer</span></a>
         </div>
      </div>
      </div>
      

</section>

<div class="box-container">

      <div class="box offer">
     
<h2 class="program-title">Join Us! Empower Your Future in Web and Software Development!</h2>
<p class="program-description">Are you a motivated young individual with a matric certificate, but financial challenges have kept you from pursuing higher education? We understand the barriers you're facing, and we’re here to help you break through them.</p><br>

<p class="program-description">Join our program and immerse yourself in the world of web and software development. Learn powerful languages for both front-end and back-end development, and gain the skills needed to excel in the tech industry. Our comprehensive training will equip you with the tools and knowledge to build a successful career, regardless of your financial background.</p><br>

<p class="program-description">Don’t let financial constraints hold you back. Seize this opportunity to invest in your future and unlock your potential in the tech world. Together, we can pave the way for your success.

Register now and take the first step towards transforming your passion into a thriving career before the yearly capacity is reached.</p></br>

</div>



<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>