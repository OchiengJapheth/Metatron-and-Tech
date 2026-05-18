<?php
session_start();
include("config/db.php");
$user_id = $_SESSION['user_id'];

// get unread notifications
$notifications = mysqli_query($conn,
    "SELECT notifications.*, users.username 
     FROM notifications
     JOIN users ON notifications.sender_id = users.id
     WHERE notifications.user_id='$user_id'
     ORDER BY notifications.created_at DESC LIMIT 10"
);

$result = ['count' => 0, 'notifications' => []];

while($n = mysqli_fetch_assoc($notifications)){
    $result['notifications'][] = [
        'id' => $n['id'],
        'message' => $n['username'].' '.$n['type'].'ed your post'
    ];
}

$result['count'] = mysqli_num_rows($notifications);

echo json_encode($result);
?>
