<?php
session_start();
include("config/db.php");
$user_id = $_SESSION['user_id'];
$chat_with = $_GET['user'];

$messages = mysqli_query($conn,
    "SELECT * FROM messages 
     WHERE (sender_id='$user_id' AND receiver_id='$chat_with') 
        OR (sender_id='$chat_with' AND receiver_id='$user_id') 
     ORDER BY created_at ASC"
);

while($m = mysqli_fetch_assoc($messages)){
    echo '<div class="'.($m['sender_id']==$user_id?'my-msg':'their-msg').'">'.$m['message'].'</div>';
}
?>