<?php
session_start();
include("config/db.php");

if(isset($_POST['comment'])){
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    mysqli_query($conn,
        "INSERT INTO comments(post_id,user_id,comment) 
         VALUES('$post_id','$user_id','$comment')"
    );

    // add notification
    $post_owner = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT user_id FROM posts WHERE id='$post_id'"
    ))['user_id'];

    if($post_owner != $user_id){
        mysqli_query($conn,
            "INSERT INTO notifications(user_id,sender_id,type,post_id)
             VALUES('$post_owner','$user_id','comment','$post_id')"
        );
    }
}

header("Location: index.php");
?>