<?php
$user_id = $_SESSION['user_id'];
$notifs = mysqli_query($conn,
    "SELECT notifications.*, users.username 
     FROM notifications 
     JOIN users ON notifications.sender_id = users.id
     WHERE notifications.user_id='$user_id' 
     ORDER BY notifications.created_at DESC 
     LIMIT 5"
);
?>

<div class="notif-dropdown">
    <button class="notif-btn">🔔
        <?php 
        $unread = mysqli_query($conn, "SELECT COUNT(*) as total FROM notifications WHERE user_id='$user_id' AND is_read=0");
        echo "(".mysqli_fetch_assoc($unread)['total'].")"; 
        ?>
    </button>

    <div class="notif-list">
        <?php while($n = mysqli_fetch_assoc($notifs)): ?>
            <div class="notif-item <?php echo $n['is_read']==0 ? 'unread':''; ?>">
                <?php if($n['type']=='like'): ?>
                    <p><strong><?php echo $n['username']; ?></strong> liked your post</p>
                <?php elseif($n['type']=='comment'): ?>
                    <p><strong><?php echo $n['username']; ?></strong> commented on your post</p>
                <?php elseif($n['type']=='friend_request'): ?>
                    <p><strong><?php echo $n['username']; ?></strong> sent you a friend request</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>


<div class="navbar">
    <h2>MySocial</h2>
    <a href="logout.php">Logout</a>
    <a href="friends.php">Friends</a>
    <a href="messages.php">Messages</a>
</div>

<form action="search.php" method="GET" class="search-form">
    <input type="text" name="q" placeholder="Search users or posts..." required>
    <button type="submit">🔍</button>
</form>

<form action="upload_story.php" method="POST" enctype="multipart/form-data" class="story-upload">
    <input type="file" name="story_media" required>
    <select name="type">
        <option value="image">Image</option>
        <option value="video">Video</option>
    </select>
    <button type="submit" name="upload">Add Story</button>
</form>

<div class="notifications">
    <button id="notif-btn">🔔 Notifications <span id="notif-count">0</span></button>
    <div id="notif-dropdown" class="notif-dropdown"></div>
</div>

