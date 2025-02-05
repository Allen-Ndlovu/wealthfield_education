<?php
include 'components/connect.php';

// Start the session if it is not already started
session_start();

// Fetch quizzes from the database
$stmt = $conn->prepare("SELECT * FROM quizzes");
$stmt->execute();
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Quizzes</title>

    <!-- Font Awesome CDN link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<h1>Available Quizzes</h1>
<ul>
    <?php if (count($quizzes) > 0): ?>
        <?php foreach ($quizzes as $quiz): ?>
            <li>
                <a href="attempt_quiz.php?quiz_id=<?= htmlspecialchars($quiz['id']) ?>"><?= htmlspecialchars($quiz['title']) ?></a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No quizzes available.</li>
    <?php endif; ?>
</ul>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link  -->
<script src="js/script.js"></script>

</body>
</html>
