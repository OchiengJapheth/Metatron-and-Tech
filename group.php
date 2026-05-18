<?php
$group_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if member
$is_member = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id='$group_id' AND user_id='$user_id'");
?>

<?php if(mysqli_num_rows($is_member) > 0): ?>
    <form method="POST">
        <button name="leave">Leave Group</button>
    </form>
<?php else: ?>
    <form method="POST">
        <button name="join">Join Group</button>
    </form>
<?php endif; ?>

<?php
if(isset($_POST['join'])){
    mysqli_query($conn, "INSERT INTO group_members(group_id,user_id) VALUES('$group_id','$user_id')");
    header("Location: group.php?id=$group_id");
}

if(isset($_POST['leave'])){
    mysqli_query($conn, "DELETE FROM group_members WHERE group_id='$group_id' AND user_id='$user_id'");
    header("Location: group.php?id=$group_id");
}
?>
