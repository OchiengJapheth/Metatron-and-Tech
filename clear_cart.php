<?php
session_start();
$_SESSION['cart'] = [];

// remove the 'cart ' key completely from the session
unset($_SESSION['cart']);

//send them back to the homepage or cart view
header("Location: view_cart.php");
exit();
?>