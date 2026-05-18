<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header</title>
   <link rel="stylesheet" href="style.css">
   <style>
    .search-btn {
      margin-left: -4px;
      border:none;
      border-radius:4px;
      padding:3px 4px;
      transition:opacity 0.3s box-shadow;
      cursor: pointer;
    }

  .search-btn:hover {
    opacity: 0.15s;
    box-shadow:4px 4px 10px rgda(0, 0, 0, 0.15);
  }

    input {
      padding:4px 6px;
      border:none;
    
      border-radius:4px;
    }

    #cart-badge {
      position: fixed;
      top: 55px;
      right: 110px;
      background-color: #28a745;
      color: white;
      font-size: 11px;
      font-weight: 600;
      border-radius: 50%;
      width: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: sans-serif;
      z-index: 100; 
      position: fixed;
    }

    .header-nav {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .cart-icon-container {
      position: relative;
      display: inline-block;
      color: #333;
      text-decoration: none;
      font-size: 16px;
      padding: 5px;
     
    }

       .main-header {
  display: grid;
  grid-template-columns: 700px 1fr 1fr;
  background-color: rgb(236, 235, 235);
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  padding-left: 48px;
  z-index: 200;
}

header {
  padding-top:50px;
}
   </style>
</head>
<body>
    <div class="main-header">
      <div>
        <p>Free delivery on orders above KES 2,000</p>
      </div>
      <div>
       <p>Need help? <span>Contact Us</span></p>
      </div>
      <div>
        <p>&#128241; 0712 345 678</p>
      </div>
    </div>

<header>
    <div class="logo">ShopEazy</div>
    <div class="search-container">
      <form action="search.php" method="get">
        <input type="text" name="query" placeholder="Search for products..." required>
        <button type="submit" class="search-btn">&#128269;</button>
      </form>
    </div>
    <div class="nav-icon">
      wishlist (<?php echo count($_SESSION['cart'] ?? []); ?>)
    </div>

    <?php
// count total item currently in the cart
$total_item = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ;
    ?>
    
    <div class="header-nav">
      <a href="view_cart.php" class="cart-icon-container">
        Cart &#128722;
      </a>
      <span id="cart-badge">
        <?php echo $total_items; ?>
      </span>
    </div>

  </header>
</body>
</html>