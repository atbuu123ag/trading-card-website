<?php
require_once 'includes/header.php';

$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id");
$all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 8;
$total_products = count($all_products);
$total_pages = ceil($total_products / $limit);
$offset = ($page - 1) * $limit;
$products = array_slice($all_products, $offset, $limit);


if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
    echo "<script>alert('Added to cart!');</script>";
}
?>
<div class="main-content" style="min-height: 100vh;">
<div style="height: 100px;"></div>
    <div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center;">
        <?php foreach ($products as $product): ?>
            <div class="product-card" style="width: 220px; background: rgba(255,255,255,0.95); border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 16px; display: flex; flex-direction: column; align-items: center; position: relative;">
                <div class="product-image" style="width: 180px; height: 180px; display: flex; align-items: center; justify-content: center; position: relative;">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 100%; max-height: 100%; border-radius: 6px;">
                </div>
                <h2 style="font-size: 18px; margin: 12px 0 4px 0; text-align: center;"><?php echo htmlspecialchars($product['name']); ?></h2>
                <p style="color: #888; font-size: 14px; margin: 0; text-align: center;"><?php echo htmlspecialchars($product['category_name']); ?></p>
                <p style="font-weight: bold; color: #007bff; font-size: 16px; margin: 8px 0 0 0;">$<?php echo number_format($product['price'], 2); ?></p>
                <form method="POST" style="display: flex; justify-content: center;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="add_to_cart" class="add-to-cart" style="background: #28a745; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 15px;">Add to Cart</button>
                </form>
                <div class="view-details" onclick="window.location.href='product.php?id=<?php echo $product['id']; ?>'" style="margin-top:8px; background: #222; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 13px; cursor: pointer; opacity: 0.85; text-align:center; width:100%;">View Details</div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php echo $i === $page ? 'style="font-weight:bold;"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
<?php require_once 'includes/footer.php'; ?>
<style>
    body {
        background: url('images/banner.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .main-content {
        background: rgba(255,255,255,0.1);
        min-height: 100vh;
    }
    .product-card {
        transition: box-shadow 0.2s;
    }
    .product-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .add-to-cart {
        transition: background 0.2s;
    }
    .add-to-cart:hover {
        background: #218838 !important;
    }
    .header-search {
        margin: 0 !important;
        padding: 0 !important;
    }
</style>