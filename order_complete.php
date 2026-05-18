<?php
session_start();
include 'db.php';

if (!isset($_SESSION['last_order_id'])) {
  header("Location: index.php");
  exit();
}

$order_id = $_SESSION['last_order_id'];

$sql = "SELECT * FROM orders WHERE id = '$order_id'";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Complition | ShopEazy</title>
 <link rel="stylesheet" href="order.css">
</head>
<body>
  <div class="success-wrapper">
    <div class="span-container">
      <span></span>
    </div>
    <h1>Thank You!</h1>
    <p class="order-confirmation">You orders have been placed successfully. We've send a confirmation to <strong><?php echo $order['email']; ?></strong></p>

    <div class="order-details-card">
      <h3>Order Details</h3>

      <div class="order-id">
        <span style="color: #888;">Order ID</span>
        <span style="font-weight: bold;">#SE-<?php echo $order['id']; ?></span>
      </div>

      <div class="order-date">
        <span style="color: #888;">Order Date</span>
        <span><?php echo date("M d, Y - h:i A", strtotime($order['created_at'])); ?></span>
      </div>

      <div class="pay-method">
        <span style="color: #888;">Payment Method</span>
        <span class="main-metod">M-pesa</span>
      </div>

      <div class="total-amount">
        <span style="font-weight: bold;">Total Paid</span>
        <span class="exact-amount">KES <?php echo number_format($order['total_paid']); ?></span>
      </div>

      <div class="sms-info">
          <p class="sms-text">You will receive updates on your order vis SMS and email. <br> 
          <strong>Track your order</strong> in your account using ID: #SE-<?php echo $order['id']; ?>
          </p>
      </div>

      <div class="cont-shopping">
        <a href="index.php" class="btn-green-large" style="width: auto; ">Continue Shopping</a>
        <a href="track_order.php" style="border: 1px solid #ddd; border-radius: 8px;">View My Orders</a>
      </div>

    </div>
  </div>
</body>
</html>