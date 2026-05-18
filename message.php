<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];

$friends = mysqli_query($conn,
   "SELECT users.id, users.username, users.profile_pic 
    FROM friends
    JOIN users ON 
        (friends.sender_id = users.id AND friends.receiver_id='$user_id'
         OR friends.receiver_id = users.id AND friends.sender_id='$user_id')
    WHERE friends.status='accepted' AND users.id != '$user_id'"
);
?>
<!DOCTYPE html>
<html>
<head>
<title>Messages</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="friends-container">
    <h2>Your Chats</h2>

    <?php while($f = mysqli_fetch_assoc($friends)): ?>
    <a href="chat.php?user=<?php echo $f['id']; ?>" class="friend-card">
        <img src="uploads/<?php echo $f['profile_pic']; ?>" class="avatar">
        <h3><?php echo $f['username']; ?></h3>
    </a>
    <?php endwhile; ?>

</div>

</body>
</html>
