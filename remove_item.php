<?php
session_start();

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  //check item existance
  if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
  }
}

header("Location: view_cart.php");
exit();
?>