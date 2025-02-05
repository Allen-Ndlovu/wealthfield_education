<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

// Determine if the current page is the home page
$is_home_page = basename($_SERVER['PHP_SELF']) === 'home.php'; // Change 'index.php' if your home page has a different name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>

    <style>
        /* Flex container for the logo link */
        .header .logo {
            display: flex;
            align-items: center;
            text-decoration: none; /* Removes underline on link */
        }

        /* Logo holder for image alignment */
        .logo-holder {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px; /* Space between logo and heading */
        }

        .logo-img {
            height: 40px; /* Adjust as needed for size */
            width: auto;
            display: inline-block;
        }

        /* Styling for the text beside the logo */
        .logo-text {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff; /* Adjust as per theme color */
        }
        /* Add any additional CSS styles here */
    </style>
</head>
<body>

<header class="header">
    <section class="flex">

    <!-- Logo Holder with Image and Heading -->
    <a href="home.php" class="logo flex">
            <div class="logo-holder">
                <img src="images/logo.png" alt="WealthField Logo" class="logo-img">
            </div>
            <span class="logo-text">WealthField IT Education</span>
        </a>

        <!-- Search form accessible only on the home page -->
        <?php if ($is_home_page): ?>
        <form action="search_course.php" method="post" class="search-form">
            <input type="text" name="search_course" placeholder="search courses..." required maxlength="100">
            <button type="submit" class="fas fa-search" name="search_course_btn"></button>
        </form>
        <?php endif; ?>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <!-- Profile content for logged-in and guest users -->
        <div class="profile">
            <?php
            if (!empty($user_id)) {
                // Logged-in user profile display
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);
                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="">
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
            <div class="flex-btn">
                <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
            </div>
            <?php
                }
            } else {
            ?>
            <!-- Guest profile display -->
            <img src="images/pic-1.jpg" alt="">
            <h3>Welcome to WealthField</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Register</a>
            </div>
            <?php } ?>
        </div>

        <!-- "Other Login Portals" dropdown for guests on home.php only -->
        <?php if (empty($user_id) && $is_home_page): ?>
        <div class="other-login-portals">
            <button class="dropdown-btn">Other Login Portals</button>
            <div class="dropdown-content">
                <a href="admin\login.php">Login as Tutor</a>
                <a href="admin_login.php">Login as Admin</a>
            </div>
        </div>
        <?php endif; ?>
        
    </section>
</header>


<!-- Navigation Section -->
<div class="side-bar">
    <div class="close-side-bar">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php
        if (!empty($user_id)) {
            // Logged-in user profile display in sidebar
            echo '<img src="uploaded_files/' . htmlspecialchars($fetch_profile['image']) . '" alt="">';
            echo "<h3>" . htmlspecialchars($fetch_profile['name']) . "</h3>";
            echo "<span>Student</span>";
            echo '<a href="profile.php" class="btn">View Profile</a>';
        } else {
            // Guest profile display in sidebar
            echo '<img src="images/pic-1.jpg" alt="">';
            echo '<h3>WealthField</h3>';
        }
        ?>
    </div>

    <nav class="navbar">
        <?php if (!empty($user_id)): ?>
            <!-- Show User Dashboard for logged-in users -->
            <a href="user_dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="view_courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
            <a href="quizzes.php"><i class="fas fa-book-open"></i><span>Quizzes</span></a>
            <a href="exam.php"><i class="fas fa-book-open"></i><span>Exams</span></a>
        <?php else: ?>
            <!-- Show Home for non-logged-in users -->
            <a href="home.php"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a> <!-- Courses link for non-logged-in users -->
        <?php endif; ?>
        <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>Tutors</span></a>

        <?php if (empty($user_id)): ?>
            <!-- Only show About and Contact for non-logged-in users -->
            <a href="about.php"><i class="fas fa-question"></i><span>About Us</span></a>
            <a href="contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
        <?php endif; ?>
    </nav>
</div>
