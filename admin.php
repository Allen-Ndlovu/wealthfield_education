<?php
include 'components/connect.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Fetch all pending students
$select_pending_users = $conn->prepare("SELECT * FROM users WHERE status = 'pending'");
$select_pending_users->execute();
$pending_students = $select_pending_users->fetchAll(PDO::FETCH_ASSOC);

// Fetch approved students
$select_approved_users = $conn->prepare("SELECT * FROM users WHERE status = 'approved'");
$select_approved_users->execute();
$approved_students = $select_approved_users->fetchAll(PDO::FETCH_ASSOC);

// Function to send email feedback
function sendFeedbackEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Email server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'wealthfielda@gmail.com';  // Replace with your email
        $mail->Password = 'Wealth2024!';  // Replace with your email password
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
    $user_id = $_POST['user_id'];
    $user_email = $_POST['email'];
    $update_status = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    $update_status->execute([$user_id]);
    
    // Send approval email
    $subject = "Registration Approved";
    $message = "<p>Your registration has been approved! You can now access the system.</p>";
    sendFeedbackEmail($user_email, $subject, $message);

    header("Location:admin_panel.php"); // Refresh the page
}

if(isset($_POST['reject'])){
    $user_id = $_POST['user_id'];
    $user_email = $_POST['email'];
    $update_status = $conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
    $update_status->execute([$user_id]);

    // Send rejection email
    $subject = "Registration Rejected";
    $message = "<p>We regret to inform you that your registration has been rejected.</p>";
    sendFeedbackEmail($user_email, $subject, $message);

    header("Location: admin_panel.php"); // Refresh the page
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include your custom CSS -->
</head>
<body>

<header class="header">
   <section class="flex">
      <a href="#" class="logo">Admin Panel</a>
      <form action="admin_logout.php" method="POST" style="display:inline;">
          <button type="submit" class="btn logout">Logout</button>
      </form>
   </section>
</header>

<section class="admin-panel">
   <h1>Pending Students</h1>

   <?php if(count($pending_students) > 0): ?>
      <table>
         <thead>
            <tr>
               <th>Full Name</th>
               <th>Email</th>
               <th>Matric Certificate</th>
               <th>Bank Statement</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach($pending_students as $student): ?>
               <tr>
                  <td><?= $student['name']; ?></td>
                  <td><?= $student['email']; ?></td>
                  <td><a href="uploaded_files/<?= $student['matric_certificate']; ?>" target="_blank">View Matric Certificate</a></td>
                  <td><a href="uploaded_files/<?= $student['bank_statement']; ?>" target="_blank">View Bank Statement</a></td>
                  <td>
                     <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?= $student['id']; ?>">
                        <input type="hidden" name="email" value="<?= $student['email']; ?>">
                        <button type="submit" name="approve" class="btn approve">Approve</button>
                        <button type="submit" name="reject" class="btn reject">Reject</button>
                     </form>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   <?php else: ?>
      <p>No pending students available.</p>
   <?php endif; ?>

   <h1>Approved Students</h1>

   <?php if(count($approved_students) > 0): ?>
      <table>
         <thead>
            <tr>
               <th>Full Name</th>
               <th>Email</th>
               <th>Matric Certificate</th>
               <th>Bank Statement</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach($approved_students as $student): ?>
               <tr>
                  <td><?= $student['name']; ?></td>
                  <td><?= $student['email']; ?></td>
                  <td><a href="uploaded_files/<?= $student['matric_certificate']; ?>" target="_blank">View Matric Certificate</a></td>
                  <td><a href="uploaded_files/<?= $student['bank_statement']; ?>" target="_blank">View Bank Statement</a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   <?php else: ?>
      <p>No approved students available.</p>
   <?php endif; ?>

</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
