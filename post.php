<?php
session_start();
include("config/db.php");

if(isset($_POST['post'])){
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    $filename = "";
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
        $filename = time().$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$filename);
    }

    $privacy = $_POST['privacy'];
$custom_ids = isset($_POST['custom_friends']) ? implode(',', $_POST['custom_friends']) : NULL;

$query = "INSERT INTO posts(user_id, content, image, privacy, custom_privacy) 
          VALUES('$user_id','$content','$filename','$privacy','$custom_ids')";
mysqli_query($conn, $query);

  
    header("Location: index.php");
}
?>