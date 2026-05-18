<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Check if like exists
$exists = mysqli_query($conn,
    "SELECT * FROM likes WHERE post_id='$post_id' AND user_id='$user_id'"
);

if(mysqli_num_rows($exists) > 0){
    // unlike
    mysqli_query($conn,
        "DELETE FROM likes WHERE post_id='$post_id' AND user_id='$user_id'"
    );
} else {
    // like post
    mysqli_query($conn,
        "INSERT INTO likes(post_id,user_id) VALUES('$post_id','$user_id')"
    );

    // add notification
    $post_owner = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT user_id FROM posts WHERE id='$post_id'"
    ))['user_id'];

    if($post_owner != $user_id){
        mysqli_query($conn,
            "INSERT INTO notifications(user_id,sender_id,type,post_id)
             VALUES('$post_owner','$user_id','like','$post_id')"
        );
    }
}


header("Location: index.php");
