

<?php
session_start();
require_once 'includes/header.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Access denied. Only admins can view this page. <a href='login.php'>Login</a></p>";
    require_once 'includes/footer.php';
    exit;
}
require_once 'includes/db_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product_id'])) {
    $id = intval($_POST['edit_product_id']);
    $name = trim($_POST['edit_name']);
    $category_id = intval($_POST['edit_category_id']);
    $price = floatval($_POST['edit_price']);
    $stock = intval($_POST['edit_stock']);
    $description = trim($_POST['edit_description']);
    $image = $_POST['current_image'];
    if (isset($_FILES['edit_image']) && $_FILES['edit_image']['size'] > 0) {
        $img_name = basename($_FILES['edit_image']['name']);
        $target = 'images/' . $img_name;
        if (move_uploaded_file($_FILES['edit_image']['tmp_name'], $target)) {
            $image = $img_name;
        }
    }
    $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, image=?, description=? WHERE id=?");
    $stmt->execute([$name, $category_id, $price, $stock, $image, $description, $id]);
    echo '<p style="color:green;">Product updated!</p>';
}

$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="css/modern-theme.css">
<div class="admin-container" style="max-width:900px;margin:40px auto;background:#fff;padding:32px;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,0.10);">
    <h1 style="margin-bottom:32px;font-size:2.2rem;color:#007bff;text-align:center;letter-spacing:1px;">Manage Products</h1>
    <a href="admin.php" style="color:#007bff;font-size:1.1rem;margin-bottom:24px;display:inline-block;"><i class="fas fa-arrow-left"></i> Back to Admin Dashboard</a>
    <div style="overflow-x:auto;margin-top:32px;">
        <table class="admin-table" style="margin-top:0;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <tr style="background:#007bff;color:#fff;font-size:1rem;">
                <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Image</th><th>Description</th><th>Actions</th>
            </tr>
            <?php foreach ($products as $p): ?>
                <tr style="background:#fff;transition:box-shadow 0.2s;">
                    <td><?php echo $p['id']; ?></td>
                    <?php if (isset($_GET['edit']) && $_GET['edit'] == $p['id']): ?>
                        <form method="POST" enctype="multipart/form-data" style="display:contents;">
                            <td><input type="text" name="edit_name" value="<?php echo htmlspecialchars($p['name']); ?>" required style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td>
                                <select name="edit_category_id" required style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id']==$p['category_id']?'selected':''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="edit_price" value="<?php echo $p['price']; ?>" step="0.01" min="0" required style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td><input type="number" name="edit_stock" value="<?php echo $p['stock']; ?>" min="0" required style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td>
                                <input type="file" name="edit_image" accept="image/*" style="margin-bottom:6px;">
                                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($p['image']); ?>">
                                <?php if (!empty($p['image'])): ?>
                                    <img src="images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" style="width:40px;height:auto;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                <?php endif; ?>
                            </td>
                            <td><textarea name="edit_description" rows="2" style="width:100%;padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"><?php echo htmlspecialchars($p['description']); ?></textarea></td>
                            <td>
                                <input type="hidden" name="edit_product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" style="background:#007bff;color:#fff;padding:8px 16px;border:none;border-radius:6px;font-size:1rem;font-weight:500;box-shadow:0 2px 8px rgba(0,123,255,0.08);transition:background 0.2s;">Save</button>
                                <a href="manage_products.php" style="padding:8px 16px;border-radius:6px;font-size:1rem;color:#222;background:#f5f7fa;margin-left:8px;text-decoration:none;">Cancel</a>
                            </td>
                        </form>
                    <?php else: ?>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo htmlspecialchars($p['category_name']); ?></td>
                        <td>$<?php echo number_format($p['price'], 2); ?></td>
                        <td><?php echo $p['stock']; ?></td>
                        <td>
                            <?php if (!empty($p['image'])): ?>
                                <img src="images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" style="width:60px;height:auto;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <?php else: ?>
                                <span style="color:#888;">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($p['description']); ?></td>
                        <td>
                            <div style="display:flex;gap:12px;justify-content:center;align-items:center;">
                                <a href="manage_products.php?edit=<?php echo $p['id']; ?>" style="flex:1;display:inline-block;text-align:center;color:#007bff;padding:8px 0;border-radius:6px;font-size:1rem;background:#f5f7fa;text-decoration:none;transition:background 0.2s;min-width:80px;">Edit</a>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="remove-btn" onclick="return confirm('Delete this product?')" style="flex:1;display:inline-block;text-align:center;background:#dc3545;color:#fff;padding:8px 0;border-radius:6px;font-size:1rem;text-decoration:none;transition:background 0.2s;min-width:80px;">Delete</a>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
