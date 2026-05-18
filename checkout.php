<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) {
  header("Location: view_cart.php");
  exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopEazy-Checkout</title>
  <link rel="stylesheet" href="checkout.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

 <header class="header-container">
    <div class="logo">
      <h3>Shop<span>Eazy</span></h3>
    </div>
    <div class="security">
      <h4 style="margin-bottom: 2px;">&#128274;Secure Checkout</h4>
    </div>
  </header>


  <div class="checkout-wrapper">


    <div class="checkout-form-section">
      <h2>Checkout</h2>
      <p>fill in your details and choose your payment method</p>

      <form action="process_order.php" method="post">
        <section class="form-group">
          <h3>1. Contact Information</h3>
          <input type="text" name="contact" placeholder="Email or Phone Number" class="modern-input">
        </section>

        <section class="form-group">
          <h3>2. Delivery Details</h3>
          <div style="display: flex; gap: 10px;">
            <input type="text" name="fname" class="modern-input" placeholder="Full Name" style="flex: 1;">
            <input type="text" name="phone" class="modern-input" placeholder="Phone Number" style="flex: 1;">
          </div>
          <br>
          <textarea name="address" class="modern-input" placeholder="Start typing your address..."></textarea>
          <input type="text" name="notes" class="modern-input" placeholder="Delivery Notes (Optional)">
        </section>

        <section class="form-group">
          <h3>Payment Method</h3>
          <p class="express">Express Checkout</p>
          <div class="payment-grid">
            <div class="pay-option mpesa">
              <span>M-pesa</span>
            </div>
            <div class="pay-option ">
              <span>Pay</span>
            </div>
            <div class="pay-option ">
              <span style="color: #4285f4; font-weight: bold;">G-<span style="color: #ea4335;">P</span><span style="color: #fbbc05;">a</span><span style="color: #34a853;">y</span>
              </span>
            </div>
          </div>

          <div class="other-choice">
            - Or pay with card -
          </div>

          <!--credit card field-->
          <input type="text" name="card_number" class="modern-input" placeholder="1234 5678 9012 3456">
          <div class="csrd-details-row">
            <input type="text" name="expiry" class="modern-input" placeholder="MM / YY" style="flex: 1;">
            <input type="text" name="cvv" class="modern-input" placeholder="CVV" style="flex: 1;">
          </div>

          <label >
            <input type="checkbox" name="save_card"> Save card for faster checkout
          </label>

        </section>

       
      </form>
    </div>

    <div class="order-summery-sidebar">
      <div class="order">
        <h3>Order Summary</h3>
         <a href="view_cart.php">Edit Cart</a>
      </div>

      <div class="cart-item-preview">
        <?php
        $subtotal = 0;
        if (!empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $id => $qty) {
            //fetch product details for each item in the cart
            $stmt = $conn->prepare("SELECT name, price, image_url FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $product = $res->fetch_assoc();

            if ($product) {

            $item_total = $product['price'] * $qty;
            $subtotal += $item_total;
         ?>        

        <!--Individual item row-->
        <div class="main-item-container">
          <div class="image-container">
            <img src="image/<?php echo $product['image_url']; ?>" >
          </div>
          <div style="flex: 1;">
            <h4><?php echo $product['name']; ?></h4>
            <p class="qty-text">Qty: <?php echo $qty; ?></p>
          </div>
          <div style="font-weight: bold; font-size: 14px;">
            KES <?php echo number_format($item_total); ?>
          </div>
        </div>

        <?php
            }
          }
        } else {
          echo "<p>Your cart is empty</p>";
        } 

      $shipping = 250;
      $tax = $subtotal * 0.16;
      $total = $subtotal + $shipping;
      ?>
      </div>

<!--Total Section-->
      <div class="total-container">
       <div class="sub">
            <span>Subtotal</span>
            <span>KES <?php echo number_format($subtotal); ?></span>
       </div>
        <div class="sub">
            <span>Shipping</span>
            <span>KES <?php echo number_format($shipping); ?></span>
       </div>
        <div class="sub">
            <span>Tax (16%)</span>
            <span>KES <?php echo number_format($tax); ?></span>
       </div>
       <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
       <div class="total">
        <span class="total-text">Total</span>
        <span class="total-cash">KES <?php echo number_format($total); ?></span>
       </div>
    </div>
</div>
</div>

    <div class="trust-budge">
      <p>Secure Checkout<br><span style="font-size: 10px;">Your data is encrypted and safe</span></p>
      <p>Easy returns<br><span style="font-size: 10px;">7-day return policy</span></p>
      <p>24/7 Support<br><span style="font-size: 10px;">We're here to help</span></p>
    </div>

   <button type="button" class="pay-green-large"><a href="track_order.php">Review Order &rarr;</a></button>

    <script>
      document.querySelector('.mpesa').addEventListener('click', () => {
        alert("M-pesa Selected! STK Push will be sent to your phone.");
      });

       document.getElementByID('hidden_total').value=" <?php echo $total; ?>";

    </script>
</body>
</html>