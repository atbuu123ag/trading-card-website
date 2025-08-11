
<?php
require_once 'includes/header.php';
session_start();

$show_products = isset($_GET['id']);

if (!$show_products) {
   
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<div class="breadcrumb" style="margin: 16px 0 24px 0; font-size: 15px; color: #555;">';
    echo '<a href="index.php" style="color:#007bff;text-decoration:none;">Home</a> &rarr; <span>Categories</span>';
    echo '</div>';
    echo '<div class="main-content" style="min-height: 100vh;">';
    echo '<h1 style="font-size: 28px; margin-bottom: 18px; text-align:center; color:#007bff;">Product Categories</h1>';
    echo '<div class="category-grid" style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center;">';
    foreach ($categories as $category) {
        echo '<div class="product-card" style="width: 220px; background: rgba(255,255,255,0.95); border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 16px; display: flex; flex-direction: column; align-items: center; position: relative;">';
        echo '<h2 style="font-size: 18px; margin: 12px 0 4px 0; text-align: center; color:#007bff;">'.htmlspecialchars($category['name']).'</h2>';
        echo '<p style="color: #888; font-size: 14px; margin: 0; text-align: center;">'.htmlspecialchars($category['description']).'</p>';
        echo '<a href="category.php?id='.$category['id'].'" style="margin-top:12px; background: #222; color: #fff; padding: 6px 16px; border-radius: 4px; font-size: 15px; cursor: pointer; opacity: 0.85; text-align:center; text-decoration:none; width:100%;">View Products</a>';
        echo '</div>';
    }
    echo '</div></div>';
} else {
   
    $cat_id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$cat_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$category) {
        echo '<p>Category not found.</p>';
        require_once 'includes/footer.php';
        exit;
    }
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = 8;
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE c.id = ?");
    $stmt->execute([$cat_id]);
    $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    echo '<div class="breadcrumb" style="margin: 16px 0 24px 0; font-size: 15px; color: #555;">';
    echo '<a href="index.php" style="color:#007bff;text-decoration:none;">Home</a> &rarr; <a href="category.php" style="color:#007bff;text-decoration:none;">Categories</a> &rarr; <span>'.htmlspecialchars($category['name']).'</span>';
    echo '</div>';
    echo '<div class="main-content" style="min-height: 100vh;">';
    echo '<h1 style="font-size: 28px; margin-bottom: 18px; text-align:center; color:#007bff;">'.htmlspecialchars($category['name']).' Products</h1>';
    echo '<div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center;">';
    if (empty($products)) {
        echo '<p>No products found.</p>';
    } else {
        foreach ($products as $product) {
            echo '<div class="product-card" style="width: 220px; background: rgba(255,255,255,0.95); border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 16px; display: flex; flex-direction: column; align-items: center; position: relative;">';
            echo '<div class="product-image" style="width: 180px; height: 180px; display: flex; align-items: center; justify-content: center; position: relative;">';
            echo '<img src="images/'.htmlspecialchars($product['image']).'" alt="'.htmlspecialchars($product['name']).'" style="max-width: 100%; max-height: 100%; border-radius: 6px;">';
            echo '</div>';
            echo '<h2 style="font-size: 18px; margin: 12px 0 4px 0; text-align: center;">'.htmlspecialchars($product['name']).'</h2>';
            echo '<p style="color: #888; font-size: 14px; margin: 0; text-align: center;">'.htmlspecialchars($product['category_name']).'</p>';
            echo '<p style="font-weight: bold; color: #007bff; font-size: 16px; margin: 8px 0 0 0;">$'.number_format($product['price'], 2).'</p>';
            echo '<form method="POST" style="display: flex; justify-content: center;">';
            echo '<input type="hidden" name="product_id" value="'.$product['id'].'">';
            echo '<button type="submit" name="add_to_cart" class="add-to-cart" style="background: #28a745; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 15px;">Add to Cart</button>';
            echo '</form>';
            echo '<div class="view-details" onclick="window.location.href=\'product.php?id='.$product['id'].'\'" style="margin-top:8px; background: #222; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 13px; cursor: pointer; opacity: 0.85; text-align:center; width:100%;">View Details</div>';
            echo '</div>';
        }
    }
    echo '</div>';
    
    if ($total_pages > 1) {
        echo '<div class="pagination" style="margin-top:24px;text-align:center;">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = $i === $page ? 'font-weight:bold;color:#007bff;' : '';
            echo '<a href="category.php?id='.$cat_id.'&page='.$i.'" style="margin:0 6px;'.$active.'">'.$i.'</a>';
        }
        echo '</div>';
    }
    echo '</div>';
}
require_once 'includes/footer.php';
?>
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
    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb span {
        color: #555;
    }
</style>