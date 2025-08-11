
<?php
session_start();
require_once 'includes/header.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<br>
    <br>
    <br>
    <p style='text-align:center;'>Your cart is empty. <a href='index.php'>Continue shopping</a></>";
    require_once 'includes/footer.php';
    exit;
}

$cart = $_SESSION['cart'];
$product_ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
    header('Location: cart.php');
    exit;
}

if (isset($_POST['checkout'])) {
    $name = trim($_POST['customer_name'] ?? '');
    $address = trim($_POST['customer_address'] ?? '');
    $phone = trim($_POST['customer_phone'] ?? '');
    $user_id = $_SESSION['user_id'] ?? null;
    if ($name && $address && $phone && count($cart_items) > 0) {
        $order = [
            'user_id' => $user_id,
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'items' => [],
            'total' => 0,
            'date' => date('Y-m-d H:i:s'),
        ];
        foreach ($cart_items as $item) {
            $qty = $cart[$item['id']];
            $order['items'][] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $qty
            ];
            $order['total'] += $item['price'] * $qty;
        }
        $order_str = json_encode($order, JSON_UNESCAPED_UNICODE);
        $username = $_SESSION['username'] ?? null;
        if ($username) {
            $user_history_file = 'database/order_history_' . $username . '.txt';
            file_put_contents($user_history_file, $order_str."\n", FILE_APPEND);
        } else {
   
            file_put_contents('database/order_history.txt', $order_str."\n", FILE_APPEND);
        }
        $_SESSION['cart'] = [];
        $success = true;
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin và kiểm tra giỏ hàng.';
    }
}


echo '<div class="cart-container" style="max-width:800px;margin:40px auto;background:#fff;padding:24px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">';
echo '<h2>Your Shopping Cart</h2>';
if (isset($success) && $success) {
    echo '<div style="color:green;">Order placed successfully! <a href="order_history.php">View order history</a></div>';
}
if (isset($error)) {
    echo '<div style="color:red;">'.$error.'</div>';
}
if (count($cart_items) === 0) {
    echo '<p>Your cart is empty.</p>';
} else {
    echo '<table border="1" cellpadding="8" style="width:100%;margin-bottom:20px;">';
    echo '<tr><th>Image</th><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Remove</th></tr>';
    $total = 0;
    foreach ($cart_items as $item) {
        $qty = $cart[$item['id']];
        $item_total = $item['price'] * $qty;
        $total += $item_total;
        echo '<tr>';
        echo '<td><img src="images/'.htmlspecialchars($item['image']).'" style="width:60px;"></td>';
        echo '<td>'.htmlspecialchars($item['name']).'</td>';
        echo '<td>$'.number_format($item['price'],2).'</td>';
        echo '<td>'.$qty.'</td>';
        echo '<td>$'.number_format($item_total,2).'</td>';
        echo '<td><a href="?remove='.$item['id'].'" class="remove-btn" style="background:red;color:#fff;padding:4px 8px;border-radius:4px;text-decoration:none;">Remove</a></td>';
        echo '</tr>';
    }
    echo '<tr><td colspan="4" style="text-align:right;"><strong>Total</strong></td><td colspan="2"><strong>$'.number_format($total,2).'</strong></td></tr>';
    echo '</table>';
    $profile_name = $profile_address = $profile_phone = '';
    $profile_complete = false;
    if (isset($_SESSION['username'])) {
        $profile_stmt = $pdo->prepare("SELECT name, address, phone FROM users WHERE username = ?");
        $profile_stmt->execute([$_SESSION['username']]);
        $profile = $profile_stmt->fetch(PDO::FETCH_ASSOC);
        if ($profile && $profile['name'] && $profile['address'] && $profile['phone']) {
            $profile_name = htmlspecialchars($profile['name']);
            $profile_address = htmlspecialchars($profile['address']);
            $profile_phone = htmlspecialchars($profile['phone']);
            $profile_complete = true;
        }
    }
    echo '<form method="POST" style="margin-top:20px;max-width:400px;">';
    echo '<h3>Customer Information</h3>';
    echo '<input type="text" name="customer_name" placeholder="Full Name" required style="width:100%;margin-bottom:8px;padding:8px;" value="'.($profile_complete ? $profile_name : '').'">';
    echo '<input type="text" name="customer_address" placeholder="Address" required style="width:100%;margin-bottom:8px;padding:8px;" value="'.($profile_complete ? $profile_address : '').'">';
    echo '<input type="text" name="customer_phone" placeholder="Phone Number" required style="width:100%;margin-bottom:8px;padding:8px;" value="'.($profile_complete ? $profile_phone : '').'">';
    if ($profile_complete) {
        echo '<div style="color:#28a745;margin-bottom:8px;">Profile info auto-filled. <a href="profile.php" style="color:#007bff;">Edit profile</a></div>';
    }
    echo '<button type="submit" name="checkout" style="background:#28a745;color:#fff;padding:10px 20px;border:none;border-radius:4px;font-size:16px;">Checkout</button>';
    echo '</form>';
}
echo '</div>';
require_once 'includes/footer.php';
?>
