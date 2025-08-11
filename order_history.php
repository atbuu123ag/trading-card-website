

<?php
session_start();
require_once 'includes/header.php';

if (!isset($_SESSION['username'])) {
    echo '<p>Please <a href="login.php">login</a> to view your order history.</p>';
    require_once 'includes/footer.php';
    exit;
}

$username = $_SESSION['username'];
$orders = [];
$user_history_file = 'database/order_history_' . $username . '.txt';
echo '<h2 style="color:#007bff; text-align:center; margin-bottom:24px;">Order History</h2>';
if (file_exists($user_history_file)) {
    $lines = file($user_history_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $order = json_decode($line, true);
        if ($order) $orders[] = $order;
    }
}
if (count($orders) === 0) {
    echo '<p style="text-align:center; color:#888; font-size:18px;">No orders found.</p>';
} else {
    foreach (array_reverse($orders) as $order) {
        echo '<div style="border:1px solid #ccc; border-radius:8px; margin-bottom:24px; padding:16px; background:#fafafa;">';
        echo '<strong>Date:</strong> '.htmlspecialchars($order['date']).'<br>';
        echo '<strong>Name:</strong> '.htmlspecialchars($order['name']).'<br>';
        echo '<strong>Address:</strong> '.htmlspecialchars($order['address']).'<br>';
        echo '<strong>Phone:</strong> '.htmlspecialchars($order['phone']).'<br>';
        echo '<strong>Products:</strong>';
        echo '<ul>';
        foreach ($order['items'] as $item) {
            echo '<li>'.htmlspecialchars($item['name']).' x '.$item['qty'].' ($'.number_format($item['price'],2).' each)</li>';
        }
        echo '</ul>';
        echo '<strong>Total:</strong> $'.number_format($order['total'],2).'<br>';
        echo '</div>';
    }
}
require_once 'includes/footer.php';
?>
