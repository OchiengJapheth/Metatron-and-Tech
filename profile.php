<?php
session_start();
include("config/db.php");

$user_id = $_GET['user'] ?? $_SESSION['user_id'];

// fetch user info
$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE id='$user_id'"
));

// fetch user posts
$posts = mysqli_query($conn,
    "SELECT * FROM posts WHERE user_id='$user_id' ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user['username']; ?>'s Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="profile-header">
    <img src="uploads/<?php echo $user['cover_photo']; ?>" class="cover-photo">
    <img src="uploads/<?php echo $user['profile_pic']; ?>" class="profile-pic">
    <h2><?php echo $user['username']; ?></h2>
    <p><?php echo $user['bio']; ?></p>
    <?php if($user_id == $_SESSION['user_id']): ?>
        <a href="edit_profile.php" class="btn">Edit Profile</a>
    <?php endif; ?>
</div>

<div class="profile-posts">
    <h3>Posts</h3>
    <?php while($row = mysqli_fetch_assoc($posts)): ?>
        <div class="post">
            <p><?php echo $row['content']; ?></p>
            <?php if($row['image']): ?>
                <img src="uploads/<?php echo $row['image']; ?>" class="post-img">
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
