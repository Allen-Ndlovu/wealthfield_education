]<?php
include 'components/connect.php';

// Check if quiz_id is set
if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Fetch quiz questions
    $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if questions were retrieved
    if ($stmt->rowCount() == 0) {
        echo "<p>No questions found for this quiz.</p>";
    }
} else {
    header('location:quizzes.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Quiz</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Inline style for h1 color */
        h1 {
            color: white;
        }
    </style>
</head>
<body>

<h1>Attempt Quiz</h1>
<form action="submit_quiz.php" method="POST">
    <input type="hidden" name="quiz_id" value="<?= htmlspecialchars($quiz_id) ?>">
    
    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $question): ?>
            <div>
                <p><?= htmlspecialchars($question['question_text']) ?></p>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="A"> <?= htmlspecialchars($question['option_a']) ?></label><br>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="B"> <?= htmlspecialchars($question['option_b']) ?></label><br>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="C"> <?= htmlspecialchars($question['option_c']) ?></label><br>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="D"> <?= htmlspecialchars($question['option_d']) ?></label><br>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No questions available for this quiz.</p>
    <?php endif; ?>
    
    <button type="submit">Submit Quiz</button>
</form>

<?php include 'components/footer.php'; ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
