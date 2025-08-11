
<?php
require_once 'includes/header.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        echo '<div style="color:#dc3545; text-align:center; margin-bottom:16px;">Invalid username or password.</div>';
    }
}
?>
<div class="main-content" style="min-height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div style="width:350px; background:rgba(255,255,255,0.97); border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,0.10); padding:32px 24px;">
        <h1 style="color:#007bff; text-align:center; margin-bottom:24px;">Login</h1>
        <form method="POST" action="login.php" style="display:flex; flex-direction:column; gap:16px;">
            <input type="text" name="username" placeholder="Username" required style="padding:10px; border-radius:5px; border:1px solid #ccc; font-size:16px;">
            <input type="password" name="password" placeholder="Password" required style="padding:10px; border-radius:5px; border:1px solid #ccc; font-size:16px;">
            <button type="submit" style="background:#007bff; color:#fff; border:none; padding:10px 0; border-radius:5px; font-size:17px; cursor:pointer; transition:background 0.2s;">Login</button>
        </form>
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
        background: rgba(255,255,255,0.1);
        min-height: 100vh;
    }
    button[type="submit"]:hover {
        background: #0056b3 !important;
    }
</style>