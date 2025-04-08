<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert quiz details
    $stmt = $conn->prepare("INSERT INTO quizzes (tutor_id, title, description) VALUES (?, ?, ?)");
    $stmt->execute([$tutor_id, $title, $description]);
    $quiz_id = $conn->lastInsertId();

    // Insert each question
    foreach ($_POST['questions'] as $key => $question_text) {
        $question_text = filter_var($question_text, FILTER_SANITIZE_STRING);
        $option_a = filter_var($_POST['option_a'][$key], FILTER_SANITIZE_STRING);
        $option_b = filter_var($_POST['option_b'][$key], FILTER_SANITIZE_STRING);
        $option_c = filter_var($_POST['option_c'][$key], FILTER_SANITIZE_STRING);
        $option_d = filter_var($_POST['option_d'][$key], FILTER_SANITIZE_STRING);
        $correct_option = filter_var($_POST['correct_option'][$key], FILTER_SANITIZE_STRING);

        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option]);
    }

    echo "<p>Quiz uploaded successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Quiz</title>

     <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<!-- custom css file link  -->
<link rel="stylesheet" href="../css/admin_style.css">
<style>
        /* Inline style for h1 color */
        h1 {
            color: white;
        }
    </style>

</head>
<body>

<?php include '../components/admin_header.php'; ?>
    
<h1>Upload a New Quiz</h1>
<form action="" method="POST">
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Description: <textarea name="description"></textarea></label><br>

    <!-- Questions Section -->
    <div id="questions-container">
        <h3>Questions</h3>
        <div class="question">
            <label>Question: <input type="text" name="questions[]" required></label><br>
            <label>Option A: <input type="text" name="option_a[]" required></label><br>
            <label>Option B: <input type="text" name="option_b[]" required></label><br>
            <label>Option C: <input type="text" name="option_c[]" required></label><br>
            <label>Option D: <input type="text" name="option_d[]" required></label><br>
            <label>Correct Answer: 
                <select name="correct_option[]" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </label>
        </div>
    </div>
    <button type="button" onclick="addQuestion()">Add Another Question</button>
    <button type="submit">Upload Quiz</button>
</form>

<!-- Link to the external JavaScript file -->
<script src="../js/admin_script.js"></script>

<?php include '../components/footer.php'; ?>
</body>
</html>
