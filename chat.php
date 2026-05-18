<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];
$friend_id = $_GET['user'];

// fetch friend info
$friend = mysqli_fetch_assoc(mysqli_query($conn,
  "SELECT * FROM users WHERE id='$friend_id'"
));
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat with <?php echo $friend['username']; ?></title>
<link rel="stylesheet" href="css/style.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// auto-refresh messages every 1 second
setInterval(function(){
    $("#chat-box").load("load_messages.php?user=<?php echo $friend_id; ?>");
}, 1000);
</script>

</head>
<body>

<?php include("includes/navbar.php"); ?>

<div class="chat-container">
    
    <div class="chat-header">
        <img src="uploads/<?php echo $friend['profile_pic']; ?>" class="avatar">
        <h3><?php echo $friend['username']; ?></h3>
    </div>

    <div id="chat-box" class="chat-box">
        <!-- Messages appear here -->
    </div>

    <form class="chat-form" method="POST" action="send_message.php">
        <input type="hidden" name="receiver" value="<?php echo $friend_id; ?>">
        <input type="text" name="message" placeholder="Type a message..." required>
        <button type="submit">Send</button>
    </form>

    <?php
session_start();
include("config/db.php");
$user_id = $_SESSION['user_id'];
$chat_with = $_GET['user']; // user we are chatting with
?>

<div class="chat-box">
    <div id="chat-messages"></div>
    <form id="chat-form">
        <input type="text" id="chat-input" placeholder="Type a message..." required>
        <button type="submit">Send</button>
    </form>
</div>

<script>
function fetchMessages(){
    fetch('fetch_messages.php?user=<?php echo $chat_with; ?>')
    .then(res => res.text())
    .then(data => { document.getElementById('chat-messages').innerHTML = data; });
}
setInterval(fetchMessages, 3000); // refresh every 3 seconds

document.getElementById('chat-form').addEventListener('submit', function(e){
    e.preventDefault();
    let msg = document.getElementById('chat-input').value;
    fetch('send_message.php', {
        method: 'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: 'receiver_id=<?php echo $chat_with; ?>&message='+encodeURIComponent(msg)
    }).then(()=>{ document.getElementById('chat-input').value=''; fetchMessages(); });
});
</script>


</div>

</body>
</html>
