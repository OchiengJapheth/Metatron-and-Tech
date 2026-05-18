<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];
$friend_id = $_GET['user'];

$get_messages = mysqli_query($conn,
   "SELECT * FROM messages 
    WHERE (sender_id='$user_id' AND receiver_id='$friend_id')
       OR (sender_id='$friend_id' AND receiver_id='$user_id')
    ORDER BY id ASC"
);

while($m = mysqli_fetch_assoc($get_messages)):
?>
    <div class="message <?php echo $m['sender_id'] == $user_id ? 'me':'them'; ?>">
        <p><?php echo $m['message']; ?></p>
    </div>
<?php endwhile; ?>