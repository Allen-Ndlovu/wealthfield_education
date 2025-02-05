<?php
include 'components/connect.php';

// Start the session if it is not already started
session_start();

// Check if the student is logged in
if (!isset($_COOKIE['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_COOKIE['user_id'];

// Function to check if the student has completed all quizzes
function hasCompletedAllQuizzes($conn, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM quizzes");
    $stmt->execute();
    $totalQuizzes = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM completed_quizzes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $completedQuizzes = $stmt->fetchColumn();

    return $totalQuizzes > 0 && $completedQuizzes == $totalQuizzes;
}

// Check if the student can access the exam
if (!hasCompletedAllQuizzes($conn, $user_id)) {
    echo "<p>Please complete all quizzes before attempting the exam.</p>";
    echo "<p><a href='quizzes.php'>Go to Quizzes</a></p>";
    exit;
}

// Fetch the exam details
$stmt = $conn->prepare("SELECT * FROM exams WHERE id = ?");
$exam_id = $_GET['exam_id']; // Get the exam ID from the URL
$stmt->execute([$exam_id]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the exam exists
if (!$exam) {
    echo "<p>Exam not found.</p>";
    exit;
}

// Fetch exam questions
$stmt = $conn->prepare("SELECT * FROM exam_questions WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total marks
$totalMarks = 0;
foreach ($questions as $question) {
    $totalMarks += $question['marks'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    // Insert the student's exam attempt
    $stmt = $conn->prepare("INSERT INTO exam_attempts (student_id, exam_id, score) VALUES (?, ?, ?)");
    
    foreach ($questions as $question) {
        $question_id = $question['id'];
        $student_answer = filter_var($_POST["answer_$question_id"], FILTER_SANITIZE_STRING);

        if ($student_answer == $question['correct_option']) {
            $score += $question['marks'];
        }
    }

    // Save the total score to the database
    $stmt->execute([$user_id, $exam_id, $score]);

    // Check if the score is more than 50%
    if ($score > ($totalMarks / 2)) {
        echo "<p>Congratulations for completing the course! You will receive a certification of completion via email within 3 business days.</p>";
    } else {
        echo "<p>Unfortunately, you did not pass. Please try again.</p>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($exam['title']) ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

<?php include 'components/user_header.php'; ?>

<h1><?= htmlspecialchars($exam['title']) ?></h1>
<p><?= htmlspecialchars($exam['description']) ?></p>

<form action="" method="POST">
    <?php foreach ($questions as $question): ?>
        <div>
            <h4><?= htmlspecialchars($question['question_text']) ?></h4>
            <label>
                <input type="radio" name="answer_<?= $question['id'] ?>" value="A" required> <?= htmlspecialchars($question['option_a']) ?>
            </label><br>
            <label>
                <input type="radio" name="answer_<?= $question['id'] ?>" value="B"> <?= htmlspecialchars($question['option_b']) ?>
            </label><br>
            <label>
                <input type="radio" name="answer_<?= $question['id'] ?>" value="C"> <?= htmlspecialchars($question['option_c']) ?>
            </label><br>
            <label>
                <input type="radio" name="answer_<?= $question['id'] ?>" value="D"> <?= htmlspecialchars($question['option_d']) ?>
            </label>
        </div>
    <?php endforeach; ?>
    <button type="submit">Submit Exam</button>
</form>

<?php include 'components/footer.php'; ?>

</body>
</html>
