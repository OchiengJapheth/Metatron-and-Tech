<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$type = $_POST['reaction'];

// check if user already reacted
$check = mysqli_query($conn, "SELECT * FROM reactions WHERE post_id='$post_id' AND user_id='$user_id'");
if(mysqli_num_rows($check) > 0){
    // update reaction
    mysqli_query($conn, "UPDATE reactions SET type='$type' WHERE post_id='$post_id' AND user_id='$user_id'");
} else {
    // insert new reaction
    mysqli_query($conn, "INSERT INTO reactions(post_id,user_id,type) VALUES('$post_id','$user_id','$type')");

    // add notification to post owner
    $post_owner = mysqli_fetch_assoc(mysqli_query($conn,"SELECT user_id FROM posts WHERE id='$post_id'"))['user_id'];
    if($post_owner != $user_id){
        mysqli_query($conn,
            "INSERT INTO notifications(user_id,sender_id,type,post_id)
             VALUES('$post_owner','$user_id','like','$post_id')"
        );
    }
}

header("Location:index.php");
?>
