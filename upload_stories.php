<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];

if(isset($_POST['upload'])){
    $file = $_FILES['story_media']['name'];
    $type = $_POST['type'];
    $filename = time().$file;
    move_uploaded_file($_FILES['story_media']['tmp_name'], "uploads/".$filename);

    mysqli_query($conn, "INSERT INTO stories(user_id, media, type) VALUES('$user_id','$filename','$type')");

    header("Location: index.php");
}
?>