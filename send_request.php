<?php
session_start();
include("config/db.php");

$sender = $_SESSION['user_id'];
$receiver = $_POST['receiver_id'];

mysqli_query($conn,
    "INSERT INTO friends(sender_id,receiver_id,status)
     VALUES('$sender','$receiver','pending')"
);

// add notification
mysqli_query($conn,
    "INSERT INTO notifications(user_id,sender_id,type)
     VALUES('$receiver','$sender','friend_request')"
);

header("Location: friends.php");
?>