<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="images/logo.png" alt="ABC Shop Logo" style="height: 50px; vertical-align: middle;">
                <span style="margin-left: 10px;">ABC Shop</span>
            </div>
            <div class="search-bar">
                <form method="GET" action="search.php">
                    <input type="text" name="query" placeholder="Search products..." style="padding: 5px; border: 1px solid #ddd; border-radius: 5px 0 0 5px; outline: none;">
                    <button type="submit" style="padding: 5px 10px; background-color: #34495e; color: white; border: none; border-radius: 0 5px 5px 0; cursor: pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li>
                        <a href="#"><i class="fas fa-list"></i> Categories</a>
                        <ul class="dropdown">
                            <?php
                            $stmt = $pdo->query("SELECT id, name FROM categories");
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($categories as $cat) {
                                echo '<li><a href="category.php?id='.$cat['id'].'">'.$cat['name'].'</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="user-menu">
                            <a href="#"><i class="fas fa-user"></i> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                            <ul class="dropdown">
                                <li><a href="profile.php"><i class="fas fa-id-card"></i> Profile</a></li>
                                <li><a href="order_history.php"><i class="fas fa-history"></i> Order History</a></li>
                                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <li><a href="admin.php"><i class="fas fa-cog"></i> Admin Panel</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>