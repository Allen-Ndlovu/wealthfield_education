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
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px 0;
}

.box {
    flex: 1;
    min-width: 300px;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
    background-color: #f4f4f4;
    text-align: center;
    transition: background-color 0.3s ease;
}

.box h3 {
    font-size: 1.5em;
    margin-bottom: 10px;
    color: #333;
}

.box-content {
    color: #555;
    margin-top: 10px;
}

.resource-link {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.resource-link:hover {
    background-color: #0056b3;
}


</style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<<section class="quick-select">

    <h1 class="heading">Home</h1>

    <div class="box-container">
        <!-- View Courses Section -->
        <div class="box">
            <h3>Courses</h3>
            <div class="box-content">
                <p>Explore a wide range of courses designed to enhance your skills in various fields, including front-end, back-end, and full-stack development.</p>
                <a href="courses.php" class="resource-link">View Courses</a>
            </div>
        </div>

        <!-- View Quizzes Section -->
        <div class="box">
            <h3>Quizzes</h3>
            <div class="box-content">
                <p>Test your knowledge and track your progress with quizzes tailored to each course, enhancing your learning experience.</p>
                <a href="quizzes.php" class="resource-link">View Quizzes</a>
            </div>
        </div>

        <!-- View Lectures Section -->
        <div class="box">
            <h3>Tutors</h3>
            <div class="box-content">
                <p>Learn from industry experts through lectures that offer in-depth insights into various technical topics and frameworks.</p>
                <a href="teachers.php" class="resource-link">View Lectures</a>
            </div>
        </div>
    </div>
</section>



<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->



<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>