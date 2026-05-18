<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  //capture data form the form
$customer_name = mysqli_real_escape_string($conn, $_POST['fname']);
$email = mysqli_real_escape_string($conn, $_POST['contact']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$total_price =(int) ($_POST['total_amount'] ?? 0);// ensure it is send from checkout

//define query
$order_query = "INSERT INTO orders(customer_name, email, total_price, status) VALUES ('$customer_name', '$email', '$total_price', 'pending')";


// run query
if (mysqli_query($conn, $order_query)) {
  $order_id = mysqli_insert_id($conn); // gets ID from db
 // save the ID to use on the complete page
  $_SESSION['last_order_id'] = $order_id;
  //clear cart
  unset($_SESSION['cart']);


  //redirect to the success page
  header("Location: processing.php?order_id=" . $order_id);
  exit();
} else {
  echo "Error: ". mysqli_error($conn);
}
}else {
  header("Location: index.php");
  exit();
}

?>