
<?php
session_start();
require_once 'includes/header.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Access denied. Only admins can view this page. <a href='login.php'>Login</a></p>";
    require_once 'includes/footer.php';
    exit;
}
?>
<h1>Admin Dashboard</h1>
<div style="max-width:500px;margin:32px auto;padding:32px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h2 style="margin-bottom:24px;">Admin Functions</h2>
    <ul style="list-style:none;padding:0;">
        <li style="margin-bottom:18px;">
            <a href="manage_products.php" style="font-size:18px;color:#007bff;text-decoration:none;">
                <i class="fas fa-box"></i> Manage Products
            </a>
        </li>
        <li style="margin-bottom:18px;">
            <a href="manage_categories.php" style="font-size:18px;color:#007bff;text-decoration:none;">
                <i class="fas fa-tags"></i> Manage Categories
            </a>
        </li>
        <li style="margin-bottom:18px;">
            <a href="manage_users.php" style="font-size:18px;color:#007bff;text-decoration:none;">
                <i class="fas fa-users"></i> Manage Users
            </a>
        </li>
    </ul>
</div>
<?php require_once 'includes/footer.php'; ?>
