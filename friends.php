<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];

// get all users except current one
$users = mysqli_query($conn,
    "SELECT * FROM users WHERE id != '$user_id'"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Friends</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="friends-container">

<h2>People You May Know</h2>

<?php while($u = mysqli_fetch_assoc($users)): 
    $uid = $u['id'];

    // check if already friends
    $check = mysqli_query($conn,
       "SELECT * FROM friends 
        WHERE (sender_id='$user_id' AND receiver_id='$uid')
        OR (sender_id='$uid' AND receiver_id='$user_id')"
    );
    $relation = mysqli_fetch_assoc($check);
?>

<div class="friend-card">
    <img src="uploads/<?php echo $u['profile_pic']; ?>" class="avatar">
    <h3><?php echo $u['username']; ?></h3>

    <?php if(!$relation): ?>
        <form method="POST" action="send_request.php">
            <input type="hidden" name="receiver_id" value="<?php echo $uid; ?>">
            <button class="btn">Add Friend</button>
        </form>

    <?php elseif($relation['status'] == 'pending' 
              && $relation['receiver_id'] == $user_id): ?>
        <form method="POST" action="accept_request.php">
            <input type="hidden" name="sender_id" value="<?php echo $uid; ?>">
            <button class="btn-accept">Accept</button>
        </form>

    <?php elseif($relation['status'] == 'pending'): ?>
        <button class="btn-pending">Pending...</button>

    <?php else: ?>
        <form method="POST" action="unfriend.php">
            <input type="hidden" name="friend_id" value="<?php echo $relation['id']; ?>">
            <button class="btn-unfriend">Unfriend</button>
        </form>
    <?php endif; ?>

</div>

<?php endwhile; ?>

</div>
</body>
</html>
