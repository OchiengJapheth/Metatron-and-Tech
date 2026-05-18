<?php
session_start();
include 'db.php';

echo "<h2>Your Shopping Cart</h2>";

$total_cart_price = 0;

if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $id => $qty) {
  $stmt = $conn->prepare("SELECT name, price, image_url FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $product = $stmt->get_result()->fetch_assoc();

  if ($product) {
    $item_total = $product['price'] * $qty;
    $total_cart_price += $item_total;

    ?>
  <div class="cart-item" style="display: flex; align-items: center; gap: 20px; padding: 15px; border-bottom: 1px solid #eee;">
    <div class="cart-img-box" style="width: 80px; height: 80px; background-color: #f9f9f9; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
      <img src="image/<?php echo $product['image_url'] ; ?>"
      style="max-width: 90%; max-height: 90%; object-fit: contain;">
    </div>

    <div style="flex: 1;">
      <h4 style="margin: 0; font-size: 16px; "><?php echo $product['name']; ?></h4>
      <p style="margin: 5px 0; color: #888; font-size: 14px;">KES <?php echo number_format($product['price']); ?></p>
    </div>

    <div style="text-align: right;">
      <p style="margin: 0; font-weight: bold; ">KES <?php echo number_format($item_total); ?></p>
      <span style="font-size: 12px; color: #666;">Qty: <?php echo $qty; ?></span>
    </div>

    <a href="remove_item.php?id=<?php echo $id; ?>" style='color:red; text-decoration: none; margin-left: 15px; font-size: 18px;'
      >&times;</a>

  </div>

  <div class="cart-action" style="display: flex; justify-content: space-between; margin-top: 30px; padding: 15px 0;">
    <a href="clear_cart.php" class="btn-clear" onclick="return confirm('Are you sure you want to empty your cart?')" style="text-decoration: none; padding: 12px 20px ; border: 1px solid #ff4d4d; border-radius: 8px; font-weight: bold; transition: all 0.2s;">
      Clear Entire Cart
    </a>

    <a href="checkout.php" class="btn-checkout" style="text-decoration: none; padding: 12px 20px ; background-color: #28a745; color: white; border-radius: 8px; font-weight: bold; transition: all 0.2s; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);">
      Proceed to Checkout &#8594;
    </a>
  </div>

  <?php
}

  }

} else {
  echo "<p style='padding:20px; text-align:center; '>Your cart is empty</p>";
}

?>