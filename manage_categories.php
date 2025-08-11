
<?php
session_start();
require_once 'includes/header.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Access denied. Only admins can view this page. <a href='login.php'>Login</a></p>";
    require_once 'includes/footer.php';
    exit;
}
require_once 'includes/db_connect.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $cat_name = trim($_POST['add_category_name']);
    if ($cat_name) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$cat_name]);
        $success = 'Category added!';
    } else {
        $error = 'Please enter a category name.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category_id'])) {
    $cat_id = intval($_POST['edit_category_id']);
    $cat_name = trim($_POST['edit_category_name']);
    if ($cat_id > 0 && $cat_name) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$cat_name, $cat_id]);
        $success = 'Category updated!';
    } else {
        $error = 'Please enter a category name.';
    }
}

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="css/modern-theme.css">
<div class="admin-container" style="max-width:900px;margin:40px auto;background:#fff;padding:32px;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,0.10);">
    <h1 style="margin-bottom:32px;font-size:2.2rem;color:#007bff;text-align:center;letter-spacing:1px;">Manage Categories</h1>
    <a href="admin.php" style="color:#007bff;font-size:1.1rem;margin-bottom:24px;display:inline-block;"><i class="fas fa-arrow-left"></i> Back to Admin Dashboard</a>
    <div style="margin:32px 0 24px 0;padding:32px;background:#f8fafc;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);max-width:600px;margin-left:auto;margin-right:auto;">
        <h2 style="margin-bottom:18px;font-size:1.3rem;color:#222;">Add Category</h2>
        <?php if ($success): ?><div style="color:green;margin-bottom:10px;font-weight:500;"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div style="color:red;margin-bottom:10px;font-weight:500;"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" style="display:flex;gap:16px;align-items:center;">
            <input type="text" name="add_category_name" placeholder="Category name" required style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;flex:1;">
            <button type="submit" name="add_category" style="background:#007bff;color:#fff;padding:12px 24px;border:none;border-radius:8px;font-size:1.1rem;font-weight:500;box-shadow:0 2px 8px rgba(0,123,255,0.08);transition:background 0.2s;">Add</button>
        </form>
    </div>
    <div style="overflow-x:auto;margin-top:32px;">
        <table class="admin-table" style="margin-top:0;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <tr style="background:#007bff;color:#fff;font-size:1rem;">
                <th>ID</th><th>Name</th><th>Actions</th>
            </tr>
            <?php foreach ($categories as $cat): ?>
                <tr style="background:#fff;transition:box-shadow 0.2s;">
                    <td><?php echo $cat['id']; ?></td>
                    <?php if (isset($_GET['edit']) && $_GET['edit'] == $cat['id']): ?>
                        <form method="POST" style="display:contents;">
                            <td><input type="text" name="edit_category_name" value="<?php echo htmlspecialchars($cat['name']); ?>" required style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;width:100%;"></td>
                            <td>
                                <input type="hidden" name="edit_category_id" value="<?php echo $cat['id']; ?>">
                                <button type="submit" style="background:#007bff;color:#fff;padding:8px 16px;border:none;border-radius:6px;font-size:1rem;font-weight:500;box-shadow:0 2px 8px rgba(0,123,255,0.08);transition:background 0.2s;">Save</button>
                                <a href="manage_categories.php" style="padding:8px 16px;border-radius:6px;font-size:1rem;color:#222;background:#f5f7fa;margin-left:8px;text-decoration:none;">Cancel</a>
                            </td>
                        </form>
                    <?php else: ?>
                        <td><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td>
                            <a href="manage_categories.php?edit=<?php echo $cat['id']; ?>" style="color:#007bff;padding:8px 16px;border-radius:6px;font-size:1rem;background:#f5f7fa;margin-right:8px;text-decoration:none;transition:background 0.2s;">Edit</a>
                            <a href="delete_category.php?id=<?php echo $cat['id']; ?>" class="remove-btn" onclick="return confirm('Delete this category?')" style="background:#dc3545;color:#fff;padding:8px 16px;border-radius:6px;font-size:1rem;text-decoration:none;transition:background 0.2s;">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
