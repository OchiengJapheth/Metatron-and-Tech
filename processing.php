<?php
include 'db.php';

//grad the tracking id straight out of the url 
$order_id = $_GET['order_id'] ?? 0;
$order_id = (int)$order_id;

if ($order_id === 0) {
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Processing Order | ShopEazy</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body{
      text-align: center;
      padding-top: 100px;
    }

    .loader {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #28a745;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }

    @keyframes spin {
      0%{ transform: rotate(0deg);}
      100% { transform: rotate(360deg);}
    }
  </style>
</head>
<body>
  <?php
include 'header.php';
  ?>

  <div class="processing-card">
    <div class="loader"></div>
      <h1>Processing Your Order...</h1>
      <p>Please wait while we confirm your payment.</p>
      <div class="status-steps">
        <span class="done">Order Received</span> 
        <span class="active">Confirming Payments</span>
        <span class="pending">Order Complete</span>
      </div>
  </div>

  <script>
    setTimeout(() => {
      window.location.href = "order_complete.php";
    }, 3000);
  </script>
</body>
</html>