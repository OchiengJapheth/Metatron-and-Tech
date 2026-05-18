<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ShopEazy | Home</title>
  <link rel="stylesheet" href="shopeazy.css">
</head>
<body>
 
<?php
include 'header.php'
?>


<main class="container">
  <!--Category Title-->
  <div class="category-header">
    <h2>Our Products</h2>
    <p>Explore our latest electronics and accessories</p>
  </div>

  <div class="product-grid">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM products");
    if (mysqli_num_rows($result) > 0) {
      while($product = mysqli_fetch_assoc($result)) {
        ?>
        <div class="product-card1">
          <a href="product.php?id=<?php echo $product['id']; ?>">
          <div class="product-image">
            <img src="image/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
          </div>
          <div class="product-info">
            <span class="category-label">
              <?php echo $product['category']; ?>
            </span>
            <h4><?php echo $product['name']; ?></h4>
            <div class="price-row">
              <span class="price">KES <?php echo number_format($product['price']); ?></span>
              <button class="add-icon">+</button>
            </div>
          </div>
        </a>
        </div>
        <?php
      }
    } else {
      echo "<p>No products found.</p>";
    }
    ?>
  </div>
</main>
</body>
</html>