<?php
session_start();

//check if ID was sent
$id = isset($_GET['id']) ?? 0;
$id = (int)$id;

// get qty
$qty = $_GET['qty'] ?? 1;
$qty = (int)$qty;

if ($id > 0) {
  //initialize cart if it does not exist yet
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }
  // add product id to the cart array
 $current_qty = $_SESSION['cart'][$id] ?? 0;
 $_SESSION['cart'][$id] = $current_qty + $qty;

// --- NEW AJAX DETECTION---
 // If called via js fetch, send JSON instead of a header redirect
 if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  echo json_encode([
    'success' => true,
    'cart_count' => array_sum($_SESSION['cart'])
  ]);
  exit();
 }

//call from non-js browser
header("Location: view_cart.php");
exit();

}
?>