<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>



<?php include 'components/user_header.php'; ?>

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/pic-8.jpg" alt="">
      </div>

      <div class="content">
         <h3 id="about-title">About Us</h3>
         <p id="about-paragraph">WealthField Online Education is a pioneering platform specializing in both back-end and front-end web development education. We are committed to providing cutting-edge, practical training that equips learners with the skills needed to excel in the rapidly evolving tech industry. Our courses are designed by industry experts and cover a comprehensive range of topics, from server-side programming to user interface design, ensuring that students gain a holistic understanding of web development.</p><br>
         <p id="about-paragraph2">Our vision is to be the leading online education platform for web development, renowned for transforming aspiring developers into industry-ready professionals. We aspire to create a community of innovative, skilled technologists who drive technological advancement and shape the digital future. By continually evolving our curriculum and leveraging the latest industry practices, we aim to empower learners to thrive in the ever-changing landscape of web development</p>
  
         
      </div>

   </div>

</section> 


<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>