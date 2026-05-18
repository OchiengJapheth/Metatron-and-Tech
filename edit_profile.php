<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));

if(isset($_POST['update'])){

    $username = $_POST['username'];
    $bio = $_POST['bio'];

    // profile pic upload
    $profile_pic = $user['profile_pic'];
    if(isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != ""){
        $profile_pic = time().$_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/".$profile_pic);
    }

    // cover photo upload
    $cover_photo = $user['cover_photo'];
    if(isset($_FILES['cover_photo']['name']) && $_FILES['cover_photo']['name'] != ""){
        $cover_photo = time().$_FILES['cover_photo']['name'];
        move_uploaded_file($_FILES['cover_photo']['tmp_name'], "uploads/".$cover_photo);
    }

    mysqli_query($conn,
        "UPDATE users SET username='$username', bio='$bio', profile_pic='$profile_pic', cover_photo='$cover_photo' WHERE id='$user_id'"
    );

    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="form-box">
    <h2>Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
        <textarea name="bio" placeholder="Bio"><?php echo $user['bio']; ?></textarea><br>
        <label>Profile Picture:</label>
        <input type="file" name="profile_pic"><br>
        <label>Cover Photo:</label>
        <input type="file" name="cover_photo"><br>
        <button name="update" type="submit">Update</button>
    </form>
</div>

</body>
</html>
