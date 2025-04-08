<?php
include '../components/connect.php';
session_start();

// Ensure the user is logged in as a tutor
if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit;
}

$tutor_id = $_COOKIE['tutor_id'];

// Initialize messages array
$message = [];

// Handle course creation
if (isset($_POST['create_course'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert course into the database
    $stmt = $conn->prepare("INSERT INTO courses (tutor_id, title, description) VALUES (?, ?, ?)");
    if ($stmt->execute([$tutor_id, $title, $description])) {
        $message[] = 'Course created successfully!';
    } else {
        $message[] = 'Failed to create course.';
    }
}

// Handle content upload
if (isset($_POST['upload_content'])) {
    $course_id = $_POST['course_id'];
    $file_type = $_POST['file_type'];
    $file = $_FILES['content_file'];

    // Directory for uploaded files
    $upload_dir = '../uploaded_files/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
    }

    // File path
    $file_path = $upload_dir . basename($file['name']);

    // Check MIME type for security
    $allowed_types = ['application/pdf', 'video/mp4'];
    if (in_array($file['type'], $allowed_types)) {
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insert content into the database
            $stmt = $conn->prepare("INSERT INTO course_content (course_id, file_type, file_path) VALUES (?, ?, ?)");
            $stmt->execute([$course_id, $file_type, $file_path]);
            $message[] = 'Content uploaded successfully!';
        } else {
            $message[] = 'Error uploading file.';
        }
    } else {
        $message[] = 'Unsupported file type uploaded.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contents">
    <h1 class="heading">Create New Course</h1>

    <?php if (!empty($message)) { foreach ($message as $msg) { echo '<p class="message">' . $msg . '</p>'; } } ?>

    <form action="" method="POST">
        <label>Course Title: <input type="text" name="title" required></label><br>
        <label>Description: <textarea name="description" required></textarea></label><br>
        <button type="submit" name="create_course" class="btn">Create Course</button>
    </form>

    <h1 class="heading">Upload Content</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Select Course:
            <select name="course_id" required>
                <?php
                // Fetch courses created by the tutor
                $select_courses = $conn->prepare("SELECT * FROM courses WHERE tutor_id = ?");
                $select_courses->execute([$tutor_id]);
                while ($course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$course['course_id']}'>{$course['title']}</option>";
                }
                ?>
            </select>
        </label><br>
        <label>File Type:
            <select name="file_type" required>
                <option value="pdf">PDF</option>
                <option value="video">Video</option>
            </select>
        </label><br>
        <label>Choose File: <input type="file" name="content_file" required></label><br>
        <button type="submit" name="upload_content" class="btn">Upload Content</button>
    </form>

    <h1 class="heading">Your Courses</h1>
    <div class="box-container">
        <?php
        $select_courses = $conn->prepare("SELECT * FROM courses WHERE tutor_id = ? ORDER BY created_at DESC");
        $select_courses->execute([$tutor_id]);
        if ($select_courses->rowCount() > 0) {
            while ($course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='box'>";
                echo "<h3 class='title'>" . htmlspecialchars($course['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($course['description']) . "</p>";

                // Fetch content for this course
                $select_content = $conn->prepare("SELECT * FROM course_content WHERE course_id = ?");
                $select_content->execute([$course['course_id']]);
                if ($select_content->rowCount() > 0) {
                    echo "<h4>Course Content:</h4><ul>";
                    while ($content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                        $file_name = basename($content['file_path']);
                        $file_path = htmlspecialchars($content['file_path']);
                        $file_type = htmlspecialchars($content['file_type']);
                        
                        // Check file type to display appropriately
                        if ($file_type === 'video') {
                            echo "<li>
                                    <video width='320' height='240' controls>
                                        <source src='$file_path' type='video/mp4'>
                                        Your browser does not support the video tag.
                                    </video>
                                    <p>{$file_name}</p>
                                  </li>";
                        } elseif ($file_type === 'pdf') {
                            echo "<li><a href='$file_path' target='_blank'>{$file_name}</a></li>";
                        } else {
                            echo "<li>Unsupported file type.</li>";
                        }
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No content uploaded yet!</p>";
                }

                echo "</div>";
            }
        } else {
            echo '<p class="empty">No courses added yet!</p>';
        }
        ?>
    </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>
</body>
</html>
