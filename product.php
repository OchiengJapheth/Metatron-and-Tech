<?php

include('db.php');

// get ID from the url
$id = isset($_GET['id']) ? $_GET['id'] : 0;

//validating the ID from the url
if ($id > 0) {
  $sql = "SELECT * FROM products WHERE id = $id";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
  } else {
    die("<h1>Product not found</h1><p>The product with ID $id does not exist in our store.</p><a href='index.php'>Back Home</a>");
  }
} else {
  die("<h1>No Product Selected</h1><p>Please select a product from the <a href='index.php'>Home Page</a>.</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name'] ?>ShopEazy</title>
  <link rel="stylesheet" href="style.css">
  <style>
    ul li {
      display: inline-block;
      position: relative;
      padding: 0.25rem 4%;
    }

    ul li a {
      text-decoration: none;
      color: #000;
    }

    hr {
      background-color: rgb(255, 254, 254);
    }

    .cart-btn-container, .wishlist-btn-container {
      margin-top:-50px;margin-left:180px;
    }

    .wishlist-btn-container {
      
    }

    .star {
  color: gold;
  font-size: 24px;
}


  </style>
</head>
<body>
  
 <?php
include 'header.php';
 ?>

 <nav class="main-nav">
        <ul>
          <li><a href="index.php"></a></li>
          <li><a href="category.php?name=Electronics">Electronics</a></li>
          <li><a href="category.php?name=Accessories" >Accessories</a></li>
          <li><a href="deals.php">Deals</a></li>
          <li><a href="track_order.php">Track Order</a></li>
        </ul>
 </nav>
 <hr>

<nav class="breadcrumbs" style="font-size: 14px; color:#888; margin-bottom: 20px;">
  <a href="index.php" style="color: #28a745; text-decoration: none;">Home</a>
  &nbsp; > &nbsp;
  <a href="search.php?query=<?php echo $p['category']; ?>" style="color: #28a745; text-decoration:none;">
    <?php echo $product['category']; ?>
  </a>
  &nbsp; > &nbsp;
  <span><?php echo $product['name']; ?></span>
</nav>

<main class="product-container">
  <div class="image-gallery">
    <img src="image/<?php echo $product['image_url'];  ?>" style="width: 100%"; border-radius="15px";>
  </div>

  <div class="product-details">
    <h1><?php echo $product['name'];  ?></h1>
    <p class="price-tag">KES <?php echo number_format($product['price']); ?> <span class="old-price">KES <?php echo number_format($product['price'] * 1.2);  ?></span></p>
    <p class="star"> &#9733;&#9733;&#9733;&#9734;&#9734; <span style="color:#000;">(4/5)</span></p>
    <p>In stoke</p>
    <p><?php echo $product['description']; ?></p>

    <div class="purchase-action">
      <div class= "quantity-selector">
    <button type="button" onclick="changeQty(-1)">-</button>
    <input type="number" name="quantity" id="display-qty" value="1" min="1" readonly>
    <button type="button" onclick="changeQty(1)">+</button>
   </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 400px 1fr;">
      <div>
        <form action="cart_handler.php" method="get" id="add-form">
      <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
      <input type="hidden" name="qty" id="hidden-qty" value="1" >
    </form>
      </div>
      <div class="cart-btn-container">
        <button type="submit" class="btn-add" data-id="<?php echo $product['id']; ?>">Add To Cart</button>
      </div>
      
        <div class="wishlist-btn-container">
      <button class="btn-wishlist">&#9825;</button>
        </div>
    </div>
    
  </div>
</main>

<section>
      <h2 style="padding-left:10%;">You May Also Like</h2>
      <div class="related-grid">
        <?php
        $current_cat = $product['category'];
        $current_id = $product['id'];
        
        $related = mysqli_query($conn, "SELECT * FROM products WHERE id != $id LIMIT 5");
        while ($item = mysqli_fetch_assoc($related)) {
              echo "
              <div class='product-card'>
                  <img src='image/{$item['image_url']}'>
                  <h4>{$item['name']}</h4>
                  <p class='price-tag'>KES ".number_format($item['price'])."</p>
              </div>
              ";
        }
        
        ?>
      </div>
</section>


<script>
  function changeQty() {
    let display = document.getElementById('display-qty');
    let hidden = document.getElementById('hidden-qty');
    let currentVal = parseInt(display.value);

    let newVal = currentVal + amount;
    if (newVal < 1) {
       newVal = 1;
       display.value = newVal;
       hidden.value = newVal;
    }
  }

  document.querySelectorAll('.btn-add').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const productId = this.getAttribute('data-id');

      //disable btn mommentarily during processing
      this.innerText = "Adding...";
      this.disabled = true;

      //fetch without reloading page
      fetch(`cart_handler.php?id=${productId}&qty=1`, {
        headers: {
          "X-Requested-With": "XMLHttpRequest"
        }
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          //update the navbar count item instantly
          document.getElementById('cart-badge').innerText = data.cart_count;

          //reset button text
          this.innerText = "Added!";
          this.style.background = "#28a745";

          setTimeout(() => {
            this.innerText = "Add to Cart";
            this.style.background = "#28a745";
            this.disabled = false;
          }, 1500);
        }
          })
          .catch(err => {
            console.error(err);
            alert("Something went wrong adding the item.");
            this.disabled = false;
          });
      });
    });
</script>
</body>
</html>