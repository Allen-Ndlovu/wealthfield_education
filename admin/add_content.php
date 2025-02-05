<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);

   // Handle Thumbnail Upload
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   // Initialize video and pdf variables
   $rename_video = $rename_pdf = null;

   // Handle Content Type
   if ($_POST['content_type'] == 'video' || $_POST['content_type'] == 'both') {
      $video = $_FILES['video']['name'];
      $video = filter_var($video, FILTER_SANITIZE_STRING);
      $video_ext = pathinfo($video, PATHINFO_EXTENSION);
      $rename_video = unique_id().'.'.$video_ext;
      $video_tmp_name = $_FILES['video']['tmp_name'];
      $video_folder = '../uploaded_files/'.$rename_video;
      $video_size = $_FILES['video']['size'];

      // Validate Video Size
      if ($video_size > 50000000) {
         $message[] = 'Video size is too large!';
      }
   }

   if ($_POST['content_type'] == 'pdf' || $_POST['content_type'] == 'both') {
      $pdf = $_FILES['pdf']['name'];
      $pdf = filter_var($pdf, FILTER_SANITIZE_STRING);
      $pdf_ext = pathinfo($pdf, PATHINFO_EXTENSION);
      $rename_pdf = unique_id().'.'.$pdf_ext;
      $pdf_tmp_name = $_FILES['pdf']['tmp_name'];
      $pdf_folder = '../uploaded_files/'.$rename_pdf;
      $pdf_size = $_FILES['pdf']['size'];

      // Validate PDF Size
      if ($pdf_size > 5000000) {
         $message[] = 'PDF size is too large!';
      }
   }

   // Validate Thumbnail Size
   if ($thumb_size > 2000000) {
      $message[] = 'Thumbnail size is too large!';
   } else {
      // Insert into the database
      $add_content = $conn->prepare("INSERT INTO `content` (id, tutor_id, playlist_id, title, description, video, thumb, pdf, status) VALUES(?,?,?,?,?,?,?,?,?)");
      $add_content->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $rename_pdf, $status]);

      // Move Uploaded Files
      move_uploaded_file($thumb_tmp_name, $thumb_folder);

      if ($_POST['content_type'] == 'video' || $_POST['content_type'] == 'both') {
         move_uploaded_file($video_tmp_name, $video_folder);
      }

      if ($_POST['content_type'] == 'pdf' || $_POST['content_type'] == 'both') {
         move_uploaded_file($pdf_tmp_name, $pdf_folder);
      }

      $message[] = 'New content uploaded!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Content</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">Upload content</h1>

   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
      }
   }
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Content type <span>*</span></p>
      <input type="radio" id="content_video" name="content_type" value="video" onchange="toggleUploadFields()" required>
      <label for="content_video">Video</label>
      <input type="radio" id="content_pdf" name="content_type" value="pdf" onchange="toggleUploadFields()">
      <label for="content_pdf">PDF</label>
      <input type="radio" id="content_both" name="content_type" value="both" onchange="toggleUploadFields()">
      <label for="content_both">Both</label>

      <p>Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter content title" class="box">
      <p>Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Course<span>*</span></p>
      <select name="course" class="box" required>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         } else {
            echo '<option value="" disabled>no course created yet!</option>';
         }
         ?>
      </select>

      <!-- File upload fields -->
      <div id="video_upload_field" style="display: none;">
         <p>Select video <span>*</span></p>
         <input type="file" name="video" accept="video/*" class="box">
      </div>

      <div id="pdf_upload_field" style="display: none;">
         <p>Select PDF <span>*</span></p>
         <input type="file" name="pdf" accept="application/pdf" class="box">
      </div>

      <p>Select thumbnail <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">

      <input type="submit" value="upload content" name="submit" class="btn">
   </form>

</section>

<?php include '../components/footer.php'; ?>

<script>
   function toggleUploadFields() {
      var contentType = document.querySelector('input[name="content_type"]:checked').value;
      var videoField = document.getElementById('video_upload_field');
      var pdfField = document.getElementById('pdf_upload_field');

      if (contentType === 'video') {
         videoField.style.display = 'block';
         pdfField.style.display = 'none';
      } else if (contentType === 'pdf') {
         videoField.style.display = 'none';
         pdfField.style.display = 'block';
      } else if (contentType === 'both') {
         videoField.style.display = 'block';
         pdfField.style.display = 'block';
      }
   }
</script>

<script src="../js/admin_script.js"></script>

</body>
</html>
