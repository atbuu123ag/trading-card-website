<?php
require_once 'includes/header.php';

$query = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';
if (empty($query)) {
    echo "<p>Please enter a search term. <a href='index.php'>Go back</a></p>";
    require_once 'includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.name LIKE ?");
$search_term = "%$query%";
$stmt->execute([$search_term]);
$all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 8;
$total_products = count($all_products);
$total_pages = ceil($total_products / $limit);
$offset = ($page - 1) * $limit;

$products = array_slice($all_products, $offset, $limit);
?>
<div class="main-content" style="min-height: 100vh;">
    <div style="height: 80px;"></div>
    <h1 style="color:#007bff; text-align:center; margin-bottom:24px;">Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
    <div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center;">
        <?php if (empty($products)): ?>
            <p style="text-align:center; color:#888; font-size:18px;">No products found.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card" style="width: 220px; background: rgba(255,255,255,0.97); border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 16px; display: flex; flex-direction: column; align-items: center; position: relative; transition: box-shadow 0.2s;">
                    <div class="product-image" style="width: 180px; height: 180px; display: flex; align-items: center; justify-content: center; position: relative;">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 100%; max-height: 100%; border-radius: 6px;">
                    </div>
                    <h2 style="font-size: 18px; margin: 12px 0 4px 0; text-align: center;"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p style="color: #888; font-size: 14px; margin: 0; text-align: center;"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <p style="font-weight: bold; color: #007bff; font-size: 16px; margin: 8px 0 0 0;">$<?php echo number_format($product['price'], 2); ?></p>
                    <form method="POST" style="display: flex; justify-content: center; margin-top:8px;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart" class="add-to-cart" style="background: #28a745; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 15px; transition: background 0.2s;">Add to Cart</button>
                    </form>
                    <div class="view-details" onclick="window.location.href='product.php?id=<?php echo $product['id']; ?>'" style="margin-top:8px; background: #222; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 13px; cursor: pointer; opacity: 0.85; text-align:center; width:100%;">View Details</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if ($total_pages > 1): ?>
        <div class="pagination" style="margin-top:24px;text-align:center;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>" <?php echo $i === $page ? 'style="font-weight:bold;color:#007bff;"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
<style>
    body {
        background: url('images/banner.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .main-content {
        background: rgba(255,255,255,0.93); 
        min-height: 100vh;
        box-shadow: 0 6px 32px rgba(0,0,0,0.12);
        border-radius: 18px;
        padding-bottom: 32px;
        margin-top: 32px;
    }
    .product-grid {
        gap: 32px !important;
    }
    .product-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        transition: box-shadow 0.2s, border-color 0.2s;
        border: 1px solid #e3e8ee;
        padding: 20px;
    }
    .product-card:hover {
        box-shadow: 0 8px 32px rgba(0,123,255,0.12);
        border-color: #007bff;
    }
    .product-image img {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e3e8ee;
        background: #f8fafc;
    }
    .add-to-cart {
        background: #28a745;
        color: #fff;
        border: none;
        padding: 10px 0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: background 0.2s;
        margin-top: 6px;
        width: 100%;
        box-shadow: 0 2px 8px rgba(40,167,69,0.08);
    }
    .add-to-cart:hover {
        background: #218838 !important;
    }
    .view-details {
        margin-top: 10px;
        background: #222;
        color: #fff;
        padding: 6px 0;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        opacity: 0.92;
        text-align: center;
        width: 100%;
        transition: background 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .view-details:hover {
        background: #007bff;
        opacity: 1;
    }
    .pagination {
        margin-top: 32px;
        text-align: center;
    }
    .pagination a {
        margin: 0 8px;
        padding: 8px 18px;
        border-radius: 8px;
        background: #f5f7fa;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
        border: 1px solid #e3e8ee;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .pagination a[style*="font-weight:bold"] {
        background: #007bff;
        color: #fff !important;
        border-color: #007bff;
    }
    @media (max-width: 600px) {
        .main-content {
            border-radius: 0;
            padding: 8px;
        }
        .product-card {
            width: 98vw !important;
            min-width: 0;
        }
        .product-grid {
            gap: 16px !important;
        }
    }