<?php
session_start();
include("config/db.php");

$query = $_GET['q'];
$user_id = $_SESSION['user_id'];

// Search users
$users = mysqli_query($conn,
    "SELECT * FROM users 
     WHERE username LIKE '%$query%' 
       AND id != '$user_id'"
);

// Search posts (public + friends posts)
$friends_ids = [];
$friends = mysqli_query($conn,
    "SELECT sender_id as friend_id FROM friends WHERE receiver_id='$user_id' AND status='accepted'
     UNION
     SELECT receiver_id as friend_id FROM friends WHERE sender_id='$user_id' AND status='accepted'"
);

while($f = mysqli_fetch_assoc($friends)){
    $friends_ids[] = $f['friend_id'];
}
$friends_ids[] = $user_id;
$friends_ids_str = implode(',', $friends_ids);

$posts = mysqli_query($conn,
    "SELECT posts.*, users.username, users.profile_pic 
     FROM posts 
     JOIN users ON posts.user_id = users.id
     WHERE ((posts.user_id IN ($friends_ids_str) AND posts.privacy='friends') 
           OR posts.privacy='public')
       AND posts.content LIKE '%$query%'
     ORDER BY posts.id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results for "<?php echo $query; ?>"</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="search-container">

    <h2>Search Results for "<?php echo $query; ?>"</h2>

    <div class="search-users">
        <h3>Users</h3>
        <?php while($u = mysqli_fetch_assoc($users)): ?>
            <a href="profile.php?user=<?php echo $u['id']; ?>" class="user-item">
                <img src="uploads/<?php echo $u['profile_pic']; ?>" class="avatar-small">
                <span><?php echo $u['username']; ?></span>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="search-posts">
        <h3>Posts</h3>
        <?php while($p = mysqli_fetch_assoc($posts)): ?>
            <div class="post-card">
                <div class="post-header">
                    <img src="uploads/<?php echo $p['profile_pic']; ?>" class="avatar-small">
                    <h4><?php echo $p['username']; ?></h4>
                    <span class="post-privacy"><?php echo $p['privacy']=='public' ? '🌍 Public':'🔒 Friends'; ?></span>
                </div>
                <p><?php echo $p['content']; ?></p>
                <?php if($p['image']): ?>
                    <img src="uploads/<?php echo $p['image']; ?>" class="post-img">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

</div>

</body>
</html>
