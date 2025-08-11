
<?php
session_start();
require_once 'includes/header.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        echo "<p>Product not found.</p>";
        require_once 'includes/footer.php';
        exit;
    }
} else {
    echo "<p>Invalid product ID.</p>";
    require_once 'includes/footer.php';
    exit;
}


if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
    echo "<script>alert('Added to cart!');</script>";
}
?>
<div class="breadcrumb" style="margin: 16px 0 24px 0; font-size: 15px; color: #555;">
    <a href="index.php" style="color:#007bff;text-decoration:none;">Home</a> &rarr; <span>Product</span>
</div>
<div class="main-content" style="max-width:700px;margin:0 auto;background:rgba(255,255,255,0.95);padding:32px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h1 style="font-size: 28px; margin-bottom: 18px; text-align:center; color:#007bff;"><?php echo htmlspecialchars($product['name']); ?></h1>
    <div class="product-details" style="display:flex;gap:32px;align-items:flex-start;flex-wrap:wrap;">
        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:220px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="flex:1;min-width:220px;">
            <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <form method="POST" style="margin-top:18px;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" name="add_to_cart" class="add-to-cart" style="background: #28a745; color: #fff; border: none; padding: 10px 24px; border-radius: 4px; cursor: pointer; font-size: 16px;">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
<style>
    body {
        background: url('images/banner.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .main-content {
        background: rgba(255,255,255,0.95);
        min-height: 400px;
    }
    .add-to-cart {
        transition: background 0.2s;
    }
    .add-to-cart:hover {
        background: #218838 !important;
    }
    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb span {
        color: #555;
    }
</style>