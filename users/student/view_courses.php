<?php
include 'components/connect.php';
session_start();

// Initialize messages array
$message = [];

// Fetch all courses
$select_courses = $conn->prepare("SELECT * FROM courses ORDER BY created_at DESC");
$select_courses->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Use this stylesheet for styling -->
    <style>
/* General Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #043171;
    margin: 0;
    padding: 0;
    color: #333; /* Neutral black for general text */
}

/* Main Content Section */
.contents {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Section Heading */
.contents .heading {
    font-size: 2em;
    color: #043171; /* Dominant color */
    text-align: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #4c66af; /* Supporting color */
    padding-bottom: 10px;
}

/* Box Container */
.box-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-around;
}

/* Individual Course Box */
.box {
    background-color:  #6495ED; /* Light tint for box background */
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    flex: 1 1 45%;
    min-width: 280px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

/* Course Title */
.box .title {
    font-size: 1.6em;
    color: #043171; /* Dominant color */
    margin-bottom: 10px;
    font-weight: bold;
}

/* Course Description */
.box p {
    font-size: 1em;
    color: #666; /* Gray for readability */
    line-height: 1.5;
    margin-bottom: 15px;
}

/* Content Heading */
.box h4 {
    font-size: 1.2em;
    color: #4c66af; /* Supporting color */
    margin-top: 10px;
    border-bottom: 1px solid #cccccc;
    padding-bottom: 5px;
}

/* Content List */
.box ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.box ul li {
    margin: 8px 0;
}

/* Content Link */
.box ul li a {
    color: #043171; /* Dominant color for links */
    font-weight: 500;
    text-decoration: none;
    font-size: 0.95em;
}

.box ul li a:hover {
    text-decoration: underline;
}

/* Missing File Notice */
.missing-file {
    color: #D32F2F;
    font-size: 0.9em;
    font-style: italic;
}

/* Empty Message */
.empty {
    text-align: center;
    font-size: 1.2em;
    color: #999;
    margin-top: 30px;
}

</style>
</head>
<body>

<section class="contents">
    <h1 class="heading">Available Courses</h1>

    <?php if ($select_courses->rowCount() > 0) { ?>
        <div class="box-container">
            <?php while ($course = $select_courses->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="box">
                    <h3 class="title"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p><?php echo htmlspecialchars($course['description']); ?></p>

                    <!-- Fetch content for this course -->
                    <?php
                    $select_content = $conn->prepare("SELECT * FROM course_content WHERE course_id = ?");
                    $select_content->execute([$course['course_id']]);
                    if ($select_content->rowCount() > 0) {
                        echo "<h4>Course Content:</h4><ul>";
                        while ($content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                            $file_name = basename($content['file_path']);
                            $file_path = 'uploaded_files/' . $file_name;

                            // Check if file exists before displaying link
                            if (file_exists($file_path)) {
                                echo "<li><a href='$file_path' target='_blank'>{$file_name}</a></li>";
                            } else {
                                echo "<li><span class='missing-file'>File {$file_name} is missing.</span></li>";
                            }
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No content uploaded yet!</p>";
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="empty">No courses available at the moment!</p>
    <?php } ?>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
