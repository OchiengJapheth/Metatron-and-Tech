<?php
include 'db.php';
$cat = mysqli_real_escape_string($conn, $_GET['name']);

$sql = "SELECT * FROM products WHERE category = '$cat'";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $cat; ?>-ShopEazy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
  <?php include 'header.php'; ?>
</header>

<div style="padding:20px 10%;">
  <h2>Browse: <?php echo $cat; ?></h2>
    <div class="related-grid">
      <?php 
      while($item = mysqli_fetch_assoc($result)) {
            echo "
            <div class='product-card'>
                <a href='product.php?id={$item['id']}'>
                  <img src='image/{$item['image_url']}'>
                  <h4>{$item['name']}</h4>
                  <p class='price-tag'>KES ".number_format($item['price'])."</p>
                </a>  
              </div>
            ";
      }
      ?>
    </div>
</div>
</body>
</html>