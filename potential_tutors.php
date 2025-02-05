<?php
session_start();
include 'components/connect.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Fetch all potential tutors
$select_potential_tutors = $conn->prepare("SELECT * FROM tutors WHERE status = 'pending'");
$select_potential_tutors->execute();
$potential_tutors = $select_potential_tutors->fetchAll(PDO::FETCH_ASSOC);

// Fetch all approved tutors
$select_approved_tutors = $conn->prepare("SELECT * FROM tutors WHERE status = 'approved'");
$select_approved_tutors->execute();
$approved_tutors = $select_approved_tutors->fetchAll(PDO::FETCH_ASSOC);

// Function to send email feedback
function sendFeedbackEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Email server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wealthfielda@gmail.com';
        $mail->Password = 'Wealth2024!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email content
        $mail->setFrom('wealthfielda@gmail.com', 'Admin');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if(isset($_POST['approve'])){
    $tutor_id = $_POST['user_id'];
    $tutor_email = $_POST['email'];
    
    // Update tutor status to approved
    $update_status = $conn->prepare("UPDATE tutors SET status = 'approved' WHERE id = ?");
    $update_status->execute([$tutor_id]);
    
    // Send approval email
    $subject = "Registration Approved";
    $message = "<p>Your application to become a tutor has been approved!</p>";
    sendFeedbackEmail($tutor_email, $subject, $message);

    // Set success message for approval
    $_SESSION['message'] = "Tutor approved successfully.";
    header("Location: potential_tutors.php");
    exit();
}

if(isset($_POST['reject'])){
    $tutor_id = $_POST['user_id'];
    $tutor_email = $_POST['email'];
    
    // Update tutor status to rejected
    $update_status = $conn->prepare("UPDATE tutors SET status = 'rejected' WHERE id = ?");
    $update_status->execute([$tutor_id]);

    // Send rejection email
    $subject = "Registration Rejected";
    $message = "<p>We regret to inform you that your application to become a tutor has been rejected.</p>";
    sendFeedbackEmail($tutor_email, $subject, $message);

    // Set success message for rejection
    $_SESSION['message'] = "Tutor rejected successfully.";
    header("Location: potential_tutors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potential Tutors</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_panel.css">
</head>
<body>

<header class="admin-nav">
    <h1 style="float: left;">Admin</h1>
    <form action="admin_logout.php" method="POST" class="logout-form" style="float: right;">
        <button type="submit" class="btn logout-btn">Logout</button>
    </form>
</header>

<section class="admin-panel">
   <h1>Potential Tutors</h1>

   <!-- Display success message if available -->
<?php if(isset($_SESSION['message'])): ?>
   <div id="admin-notification" class="message">
      <?= $_SESSION['message']; ?>
   </div>
   <?php unset($_SESSION['message']); // Clear message after displaying ?>
<?php endif; ?>

   <?php if(count($potential_tutors) > 0): ?>
      <table>
         <thead>
            <tr>
               <th>Full Name</th>
               <th>Profession</th>
               <th>Email</th>
               <th>CV</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach($potential_tutors as $tutor): ?>
               <tr>
                  <td><?= htmlspecialchars($tutor['name']); ?></td>
                  <td><?= htmlspecialchars($tutor['profession']); ?></td>
                  <td><?= htmlspecialchars($tutor['email']); ?></td>
                  <td><a href="uploaded_files/<?= htmlspecialchars($tutor['cv']); ?>" target="_blank">View CV</a></td>
                  <td>
                     <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?= $tutor['id']; ?>">
                        <input type="hidden" name="email" value="<?= $tutor['email']; ?>">
                        <button type="submit" name="approve" class="btn approve">Approve</button>
                        <button type="submit" name="reject" class="btn reject">Reject</button>
                     </form>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   <?php else: ?>
      <p>No potential tutors available.</p>
   <?php endif; ?>

   <h1>Approved Tutors</h1>

   <?php if(count($approved_tutors) > 0): ?>
      <table>
         <thead>
            <tr>
               <th>Full Name</th>
               <th>Profession</th>
               <th>Email</th>
               <th>CV</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach($approved_tutors as $tutor): ?>
               <tr>
                  <td><?= htmlspecialchars($tutor['name']); ?></td>
                  <td><?= htmlspecialchars($tutor['profession']); ?></td>
                  <td><?= htmlspecialchars($tutor['email']); ?></td>
                  <td><a href="uploaded_files/<?= htmlspecialchars($tutor['cv']); ?>" target="_blank">View CV</a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   <?php else: ?>
      <p>No approved tutors available.</p>
   <?php endif; ?>

   <div class="navigation-container">
       <a href="admin_panel.php" class="navigation-button">Back to Pending Students</a>
   </div>
  
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
