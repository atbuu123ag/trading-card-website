
<?php
require_once 'includes/header.php';
$register_error = '';
$register_success = '';
$input_username = '';
$input_email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_username = trim($_POST['username']);
    $input_email = trim($_POST['email']);
    $input_password = $_POST['password'];
    
    if (strlen($input_username) < 4 || strlen($input_username) > 32) {
        $register_error = 'Username must be 4-32 characters.';
    } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $register_error = 'Invalid email format.';
    } elseif (strlen($input_password) < 6) {
        $register_error = 'Password must be at least 6 characters.';
    } else {
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$input_username, $input_email]);
        $exists = $stmt->fetchColumn();
        if ($exists > 0) {
            $register_error = 'Username or email already exists.';
        } else {
            $password_hash = password_hash($input_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$input_username, $input_email, $password_hash])) {
                $register_success = 'Registration successful! <a href="login.php" style="color:#007bff;">Login here</a>';
                $input_username = '';
                $input_email = '';
            } else {
                $register_error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<div class="main-content" style="min-height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div style="width:350px; background:rgba(255,255,255,0.97); border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,0.10); padding:32px 24px;">
        <h1 style="color:#007bff; text-align:center; margin-bottom:24px;">Register</h1>
        <?php if ($register_error): ?>
            <div style="color:#dc3545; text-align:center; margin-bottom:16px; font-weight:500;"> <?php echo $register_error; ?> </div>
        <?php elseif ($register_success): ?>
            <div style="color:#28a745; text-align:center; margin-bottom:16px; font-weight:500;"> <?php echo $register_success; ?> </div>
        <?php endif; ?>
        <form method="POST" action="register.php" style="display:flex; flex-direction:column; gap:16px;">
            <input type="text" name="username" placeholder="Username" required style="padding:10px; border-radius:5px; border:1px solid #ccc; font-size:16px;" value="<?php echo htmlspecialchars($input_username); ?>">
            <input type="email" name="email" placeholder="Email" required style="padding:10px; border-radius:5px; border:1px solid #ccc; font-size:16px;" value="<?php echo htmlspecialchars($input_email); ?>">
            <input type="password" name="password" placeholder="Password" required style="padding:10px; border-radius:5px; border:1px solid #ccc; font-size:16px;">
            <button type="submit" style="background:#007bff; color:#fff; border:none; padding:10px 0; border-radius:5px; font-size:17px; cursor:pointer; transition:background 0.2s;">Register</button>
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