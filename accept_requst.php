<?php
session_start();
include("config/db.php");

$receiver = $_SESSION['user_id'];
$sender = $_POST['sender_id'];

mysqli_query($conn,
   "UPDATE friends 
    SET status='accepted' 
    WHERE sender_id='$sender' AND receiver_id='$receiver'"
);

header("Location: friends.php");
?>