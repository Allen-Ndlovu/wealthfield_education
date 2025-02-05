<?php
include 'components/connect.php';

// Check if the quiz_id and answers are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'], $_POST['answers'])) {
    $quiz_id = $_POST['quiz_id'];
    $answers = $_POST['answers'];

    // Initialize score
    $score = 0;

    // Fetch correct answers for the quiz questions
    $stmt = $conn->prepare("SELECT id, correct_option FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quiz_id]);
    $correct_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate score based on correct answers
    foreach ($correct_answers as $question) {
        if (isset($answers[$question['id']]) && $answers[$question['id']] === $question['correct_option']) {
            $score++;
        }
    }

    // Get the user's ID (Assuming it's stored in a cookie)
    $user_id = $_COOKIE['user_id'] ?? null; // Adjust this as necessary based on your authentication

    // Insert the attempt into the database
    if ($user_id) {
        $stmt = $conn->prepare("INSERT INTO attempts (quiz_id, user_id, score, attempt_date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$quiz_id, $user_id, $score]);

        echo "<p>Quiz submitted successfully!</p>";
        echo "<p>Your Score: $score</p>";
    } else {
        echo "<p>Error: User not logged in.</p>";
    }
} else {
    echo "<p>Error: Invalid request.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Submission</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="container">
    <?php
    if ($user_id) {
        echo "<p class='success-message'><i class='fas fa-check-circle'></i> Quiz submitted successfully!</p>";
        echo "<p class='score'>Your Score: $score</p>";
    } else {
        echo "<p class='error-message'><i class='fas fa-exclamation-circle'></i> Error: User not logged in.</p>";
    }
    ?>
    <a href="quizzes.php"><button>Back to Quizzes</button></a>
</div>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
