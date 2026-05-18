<?php
include ('db.php');
include ('header.php');
$search_query = $_GET['query'] ?? '';

$sql = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results for "<?php echo $search_query; ?>"</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Resulte for "<?php echo $search_query; ?>"</h2>
  <div class="related-grid">
    <?php
    $search_query = $_GET['query'] ?? '';
    $sql = "SELECT * FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $term ="%" . $search_query . "%";
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      while($product = $result->fetch_assoc()) {
        ?>
        <div class="product-card">
          <img src="image/<?php echo $product['image_url']; ?>" style="width: 100px;">
          <h4><?php echo $product['name']; ?></h4>
          <p>KES <?php echo number_format($product['price'])?></p>
          <a href="product.php?id=<?php echo $product['id']; ?>" style= "color:green; text-decoration:none;">View Product</a>
        </div>
        <?php
      }
  } else {
    echo "<p>No products found matching your search.</p>";
  }
    ?>
  </div>
</body>
</html>