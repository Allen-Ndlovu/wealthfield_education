<?php
include '../components/connect.php';

// Check if tutor is logged in
if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    header('location:login.php');
    exit;
}

// Fetch attempts along with user and quiz details
$stmt = $conn->prepare("
    SELECT attempts.user_id, attempts.quiz_id, attempts.score, attempts.attempt_date, 
           users.name AS user_name, quizzes.title AS quiz_title
    FROM attempts 
    JOIN users ON attempts.user_id = users.id 
    JOIN quizzes ON attempts.quiz_id = quizzes.id
    ORDER BY attempts.attempt_date DESC
");
$stmt->execute();
$attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attempts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
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

<h1>Quiz Attempts</h1>
<table border="1">
    <tr>
      
        <th>Student Name</th>
        <th>Quiz Title</th>
        <th>Score</th>
        <th>Attempt Date</th>
    </tr>
    <?php if ($attempts): ?>
        <?php foreach ($attempts as $attempt): ?>
            <tr>
                
                <td><?= htmlspecialchars($attempt['user_name']); ?></td>
                <td><?= htmlspecialchars($attempt['quiz_title']); ?></td>
                <td><?= htmlspecialchars($attempt['score']); ?></td>
                <td><?= htmlspecialchars($attempt['attempt_date']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No attempts recorded.</td>
        </tr>
    <?php endif; ?>
</table>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
