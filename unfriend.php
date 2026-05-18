<?php
session_start();
include("config/db.php");

$friend_id = $_POST['friend_id'];

mysqli_query($conn,
    "DELETE FROM friends WHERE id='$friend_id'"
);

header("Location: friends.php");
?>