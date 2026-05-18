<?php
session_start();
include("config/db.php");

if(isset($_POST['create'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $privacy = $_POST['privacy'];
    $owner_id = $_SESSION['user_id'];

    mysqli_query($conn, "INSERT INTO groups(name,description,privacy,owner_id)
                         VALUES('$name','$desc','$privacy','$owner_id')");

    $group_id = mysqli_insert_id($conn);

    // Owner auto-joins the group
    mysqli_query($conn, "INSERT INTO group_members(group_id,user_id) VALUES('$group_id','$owner_id')");

    header("Location: group.php?id=$group_id");
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Group Name" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <select name="privacy">
        <option value="public">Public</option>
        <option value="private">Private</option>
    </select><br>
    <button name="create">Create Group</button>
</form>
