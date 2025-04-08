<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    header('location:login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert exam details
    $stmt = $conn->prepare("INSERT INTO exams (tutor_id, title, description, total_marks) VALUES (?, ?, ?, ?)");
    $total_marks = 100; // Fixed total marks for the exam
    $stmt->execute([$tutor_id, $title, $description, $total_marks]);
    $exam_id = $conn->lastInsertId();

    // Insert multiple choice questions (30 marks total)
    foreach ($_POST['mcq_questions'] as $key => $question_text) {
        $question_text = filter_var($question_text, FILTER_SANITIZE_STRING);
        $option_a = filter_var($_POST['mcq_option_a'][$key], FILTER_SANITIZE_STRING);
        $option_b = filter_var($_POST['mcq_option_b'][$key], FILTER_SANITIZE_STRING);
        $option_c = filter_var($_POST['mcq_option_c'][$key], FILTER_SANITIZE_STRING);
        $option_d = filter_var($_POST['mcq_option_d'][$key], FILTER_SANITIZE_STRING);
        $correct_option = filter_var($_POST['mcq_correct_option'][$key], FILTER_SANITIZE_STRING);

        $stmt = $conn->prepare("INSERT INTO exam_questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$exam_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option, 10]); // Each MCQ = 10 marks
    }

    // Insert short questions (10 marks each)
    foreach ($_POST['short_questions'] as $key => $short_question) {
        $short_question = filter_var($short_question, FILTER_SANITIZE_STRING);
        $solution = filter_var($_POST['short_solutions'][$key], FILTER_SANITIZE_STRING); // Solution for short question

        $stmt = $conn->prepare("INSERT INTO exam_questions (exam_id, question_text, solution, marks) VALUES (?, ?, ?, ?)");
        $stmt->execute([$exam_id, $short_question, $solution, 10]); // Each short question = 10 marks
    }

    // Insert long questions (25 marks each)
    foreach ($_POST['long_questions'] as $key => $long_question) {
        $long_question = filter_var($long_question, FILTER_SANITIZE_STRING);
        $solution = filter_var($_POST['long_solutions'][$key], FILTER_SANITIZE_STRING); // Solution for long question

        $stmt = $conn->prepare("INSERT INTO exam_questions (exam_id, question_text, solution, marks) VALUES (?, ?, ?, ?)");
        $stmt->execute([$exam_id, $long_question, $solution, 25]); // Each long question = 25 marks
    }

    echo "<p>Exam uploaded successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Exam</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<h1>Upload a New Exam</h1>
<form action="" method="POST">
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Description: <textarea name="description" rows="4" cols="50"></textarea></label><br>

    <!-- Multiple Choice Questions -->
    <h3>Multiple Choice Questions (Total: 30 Marks)</h3>
    <div id="mcq-container">
        <div class="mcq-question">
            <label>Question:</label><br>
            <input type="text" name="mcq_questions[]" required><br>
            <label>Option A:</label><br>
            <input type="text" name="mcq_option_a[]" required><br>
            <label>Option B:</label><br>
            <input type="text" name="mcq_option_b[]" required><br>
            <label>Option C:</label><br>
            <input type="text" name="mcq_option_c[]" required><br>
            <label>Option D:</label><br>
            <input type="text" name="mcq_option_d[]" required><br>
            <label>Correct Answer:</label>
            <select name="mcq_correct_option[]" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
            <button type="button" onclick="removeQuestion(this)">Remove Question</button>
        </div>
    </div>
    <input type="hidden" name="mcq_count" value="1">
    <button type="button" onclick="addMcqQuestion()">Add Another MCQ</button>

    <!-- Short Questions -->
    <h3>Short Questions (Total: 20 Marks)</h3>
    <div id="short-container">
        <div class="short-question">
            <label>Question:</label><br>
            <textarea name="short_questions[]" rows="3" cols="50" required></textarea><br>
            <label>Solution:</label><br>
            <textarea name="short_solutions[]" rows="3" cols="50" required></textarea><br>
            <button type="button" onclick="removeQuestion(this)">Remove Question</button>
        </div>
    </div>
    <button type="button" onclick="addShortQuestion()">Add Another Short Question</button>

    <!-- Long Questions -->
    <h3>Long Questions (Total: 50 Marks)</h3>
    <div id="long-container">
        <div class="long-question">
            <label>Question:</label><br>
            <textarea name="long_questions[]" rows="4" cols="50" required></textarea><br>
            <label>Solution:</label><br>
            <textarea name="long_solutions[]" rows="4" cols="50" required></textarea><br>
            <button type="button" onclick="removeQuestion(this)">Remove Question</button>
        </div>
    </div>
    <button type="button" onclick="addLongQuestion()">Add Another Long Question</button>

    <button type="submit">Upload Exam</button>
</form>

<script src="../js/admin_script.js"></script>
<?php include '../components/footer.php'; ?>
</body>
</html>
