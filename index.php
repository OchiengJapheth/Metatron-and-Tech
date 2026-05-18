<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

$user_id = $_SESSION['user_id'];

// Get friend IDs
$friends_ids = [];
$friends = mysqli_query($conn,
    "SELECT sender_id as friend_id FROM friends WHERE receiver_id='$user_id' AND status='accepted'
     UNION
     SELECT receiver_id as friend_id FROM friends WHERE sender_id='$user_id' AND status='accepted'"
);
while($f = mysqli_fetch_assoc($friends)) $friends_ids[] = $f['friend_id'];
$friends_ids[] = $user_id;

// Fetch posts
$posts = mysqli_query($conn,
    "SELECT posts.*, users.username, users.profile_pic
     FROM posts
     JOIN users ON posts.user_id = users.id
     WHERE
     (posts.privacy='public') OR
     (posts.privacy='friends' AND posts.user_id IN (".implode(',',$friends_ids).")) OR
     (posts.privacy='custom' AND (
         FIND_IN_SET('$user_id', posts.custom_privacy) > 0 OR posts.user_id='$user_id'
     ))
     ORDER BY posts.id DESC"
);


?>

<!DOCTYPE html>
<html>
<head>
<title>News Feed</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="feed-container">

    <div class="new-post-box">
        <form action="post.php" method="POST" enctype="multipart/form-data">
           <textarea name="content" placeholder="What's on your mind?" required></textarea>
<input type="file" name="image">
<select name="privacy">
    <option value="public">Public</option>
    <option value="friends">Friends Only</option>
    <p class="post-privacy">
    <?php echo $row['privacy'] == 'public' ? '🌍 Public' : '🔒 Friends Only'; ?>
</p>
</select>
<button type="submit" name="post">Post</button>
        </form>
    </div>

    <?php while($row = mysqli_fetch_assoc($posts)): ?>
        <div class="post">
          <?php
$post_id = $row['id'];
$user_id = $_SESSION['user_id'];

// Check if user liked this post
$check_like = mysqli_query($conn,
    "SELECT * FROM likes WHERE post_id='$post_id' AND user_id='$user_id'"
);

$liked = mysqli_num_rows($check_like) > 0;

// Count likes
$count_likes = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM likes WHERE post_id='$post_id'"
);
$total_likes = mysqli_fetch_assoc($count_likes)['total'];
?>

<form action="like.php" method="POST">
      <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <button class="like-btn <?php echo $liked ? 'liked':''; ?>">
        ❤ <?php echo $liked ? 'Unlike' : 'Like'; ?>
    </button>
</form>

<!-- Comment Form -->
<form action="comment.php" method="POST" class="comment-form">
    <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
    <input type="text" name="comment" placeholder="Write a comment..." required>
    <button type="submit">Comment</button>
</form>

<?php
$postId = $row['id'];

$get_comments = mysqli_query($conn,
    "SELECT comments.*, users.username, users.profile_pic 
     FROM comments 
     JOIN users ON comments.user_id = users.id 
     WHERE post_id='$postId' 
     ORDER BY comments.id ASC"
);

while ($c = mysqli_fetch_assoc($get_comments)): ?>
    <div class="comment-box">
        <img src="uploads/<?php echo $c['profile_pic']; ?>" class="comment-avatar">
        <p>
            <strong><?php echo $c['username']; ?></strong>
            <span><?php echo $c['comment']; ?></span>
        </p>
    </div>
<?php endwhile; ?>

<p class="likes-count"><?php echo $total_likes; ?> likes</p>
            <img src="uploads/<?php echo $row['profile_pic']; ?>" class="avatar">
            <h3><?php echo $row['username']; ?></h3>
            <p><?php echo $row['content']; ?></p>
            <?php if($row['image']): ?>
                <img src="uploads/<?php echo $row['image']; ?>" class="post-img">
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <div class="container">

  <!-- Left Sidebar -->
  <div class="sidebar-left">
    <div class="profile-box">
      <img src="uploads/<?php echo $_SESSION['profile_pic']; ?>" class="profile-avatar">
      <h3><?php echo $_SESSION['username']; ?></h3>
    </div>
    <div class="friends-box">
      <h4>Friends</h4>
      <?php
      $friends_list = mysqli_query($conn,
        "SELECT users.username, users.profile_pic FROM friends 
         JOIN users ON (users.id = friends.sender_id OR users.id = friends.receiver_id)
         WHERE (friends.sender_id='$_SESSION[user_id]' OR friends.receiver_id='$_SESSION[user_id]') 
           AND users.id != '$_SESSION[user_id]' AND friends.status='accepted'"
      );
      while($f = mysqli_fetch_assoc($friends_list)):
      ?>
        <div class="friend-item">
          <img src="uploads/<?php echo $f['profile_pic']; ?>" class="friend-avatar">
          <span><?php echo $f['username']; ?></span>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <div class="stories-bar">
    <?php
    $stories = mysqli_query($conn,
        "SELECT stories.*, users.username, users.profile_pic 
         FROM stories 
         JOIN users ON stories.user_id=users.id
         WHERE created_at >= NOW() - INTERVAL 1 DAY
         ORDER BY created_at DESC"
    );
    while($s = mysqli_fetch_assoc($stories)):
    ?>
        <div class="story-item">
            <img src="uploads/<?php echo $s['profile_pic']; ?>" class="story-avatar" onclick="viewStory('<?php echo $s['media']; ?>','<?php echo $s['type']; ?>')">
            <span><?php echo $s['username']; ?></span>
        </div>
    <?php endwhile; ?>
</div>

<!-- Story Modal -->
<div id="storyModal" class="story-modal" style="display:none;">
    <span class="close" onclick="closeStory()">×</span>
    <img id="storyImage" style="max-width:100%; max-height:90%;">
    <video id="storyVideo" controls style="max-width:100%; max-height:90%; display:none;"></video>
</div>


  <!-- Center Feed -->
  <div class="feed">
    <?php include("post_form.php"); ?>
    <?php while($row = mysqli_fetch_assoc($posts)): ?>
      <div class="post-card">
        <div class="post-header">
          <img src="uploads/<?php echo $row['profile_pic']; ?>" class="avatar-small">
          <h4><?php echo $row['username']; ?></h4>
          <span class="post-privacy"><?php echo $row['privacy']=='public' ? '🌍 Public':'🔒 Friends'; ?></span>
        </div>
        <p><?php echo $row['content']; ?></p>
        <?php if($row['image']): ?>
          <img src="uploads/<?php echo $row['image']; ?>" class="post-img">
        <?php endif; ?>
          <div class="post-actions">
    <div class="reaction-btns">
        <form method="POST" action="react.php">
            <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
            <select name="reaction" onchange="this.form.submit()">
                <option value="">React</option>
                <option value="like">👍 Like</option>
                <option value="love">❤ Love</option>
                <option value="haha">😂 Haha</option>
                <option value="wow">😮 Wow</option>
                <option value="sad">😢 Sad</option>
                <option value="angry">😡 Angry</option>
            </select>
        </form>
    </div>
    <span class="reaction-count">
        <?php
        $r_count = mysqli_query($conn,"SELECT type, COUNT(*) as cnt FROM reactions WHERE post_id='".$row['id']."' GROUP BY type");
        while($rc = mysqli_fetch_assoc($r_count)){
            echo $rc['type'].": ".$rc['cnt']." ";
        }
        ?>
  </span>
</div>
          </form>
          <form action="comment.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
            <input type="text" name="comment" placeholder="Write a comment..." required>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Right Sidebar -->
  <div class="sidebar-right">
    <h4>Suggestions</h4>
    <!-- Can populate with users who are not friends -->
    
    <h4>Pages</h4>
    <?php
    $pages = mysqli_query($conn, "SELECT * FROM pages ORDER BY created_at DESC");
    while($p = mysqli_fetch_assoc($pages)):
    ?>
        <a href="page.php?id=<?php echo $p['id']; ?>"><?php echo $p['name']; ?></a><br>
    <?php endwhile; ?>
</div>


<div class="sidebar-left">
    <h4>Groups</h4>
    <?php
    $group_posts = mysqli_query($conn,
    "SELECT posts.*, users.username, users.profile_pic 
     FROM posts
     JOIN users ON posts.user_id=users.id
     WHERE posts.group_id='$group_id'
     ORDER BY posts.id DESC"
);
    while($g = mysqli_fetch_assoc($groups)):
    ?>
        <a href="group.php?id=<?php echo $g['id']; ?>"><?php echo $g['name']; ?></a><br>
    <?php endwhile;?>
</div>

</div>
</div>
 <script src="script.js"></script>
</body>
</html>
