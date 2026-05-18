<?php include 'db.php'; 
include 'header.php';

$order_id = '';
$order = null;
$error_msg = '';

// check if id is submitted by user
if(isset($_GET['order_id'])) {
  // strip out any'#'symbol
  $order_id = str_replace('#', '', $_GET['order_id']);
  $order_id = (int)$order_id;

  if($order_id > 0) {
// fetch the order status from database
 $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $order = $stmt->get_result()->fetch_assoc();

  if(!$order) {
    $error_msg = "We couldn't find an order with ID #". $order_id . ". Please double-check your receipt.";
  }
  } else {
    $error_msg = "Please enter a valid numeric Order ID.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopEazy</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .container {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    h2{
      text-align: center;
      margin-bottom: 20px;

    }

    .id-input {
      flex: 1;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    form {
      margin-bottom: 30px;
    }

    .track-btn {
      padding: 12px 24px;
      background-color: #28a745;
      color: white;
      cursor: pointer;
      border: none;
      border-radius: 8px;
      font-weight: 600;

    }

    .err-msg {
      background-color: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .order-details {
      border-top: 1px solid #eee;
      padding-top: 20px;
    }

    h3 {
      margin-bottom: 15px;
    }

    span {
      background-color: #fef3c7; 
      color: #d97706; 
      padding: 10px 20px ; 
      border-radius: 20px; 
      font-weight: 600; 
      font-size: 18px; 
      display: inline-block; 
      text-decoration: uppercase;
    }

    .span-text {
      font-size: 13px;
      color: #888;
      margin-top: 10px;
    }

    .status {
        background-color: #e2e8f0;
        color: #475569; 
        padding: 10px 20px;
          border-radius: 20px; 
       font-weight: 600;
          font-size: 18px; 
          display: inline-block; 
          text-transform: uppercase;
    }
  </style>
</head>
<body>
  
  <main class="container">
<h2>Track Your Order</h2>

<form action="track_order.php" method="get">
  <div style="display: flex; gap: 10px;">
<input type="text" name="order_id" value="<?php echo htmlspaecialchars($order_id); ?>" placeholder="Enter your Order ID (e.g., 12)" class="id-input">
<button type="submit" class="track-btn">Track</button>
  </div>
</form>

<?php if ($error_msg): ?>
<div class="err-msg">
  <?php echo $error_msg; ?>
</div>
<?php endif;?>

<?php if ($order): ?>
<div class="order-details">
  <h3>Order Details (#<?php echo $order['id']; ?>)</h3>
  <p style="margin: 5px 0;"><strong>Customer Name:</strong><?php echo htmlspecialchars($order['customer_name']); ?></p>
  <p style="margin: 5px 0;"><strong>Total Bill</strong> KES <?php echo number_format($order['total_price'])?></p>

  <div style="margin-top: 30px; text-align: center;">
    <p style="font-size: 14px; color: #666; margin-bottom: 10px;">Current Fulfillment Status:</p>

    <?php if ($order['status'] == 'pending'): ?>
    <span >
      Pending / processing
    </span>
    <p class="span-text">The shop administrator is reviwing your payment entry.</p>
    <?php elseif ($order['status'] == 'completed'): ?>
     <span style="background-color: #dcfce7; color: #15803d;">
      Completed / Dispatched
    </span>
    <p class="span-text">Your parcel has been handled over to the courier!</p>
    <?php else: ?>
    <span class="status">
      <?php echo htmlspecialchars($order['status']); ?>
    </span>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>

  </main>
  
</body>
</html>