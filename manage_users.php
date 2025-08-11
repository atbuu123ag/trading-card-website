

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $uname = trim($_POST['add_username']);
    $name = trim($_POST['add_name']);
    $address = trim($_POST['add_address']);
    $phone = trim($_POST['add_phone']);
    $email = trim($_POST['add_email']);
    $role = $_POST['add_role'];
    $password = $_POST['add_password'];
    if ($uname && $email && $password) {
       
        $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->execute([$uname]);
        if ($check_stmt->fetch()) {
            $error = 'Username already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, name, address, phone, email, role, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$uname, $name, $address, $phone, $email, $role, $hashed]);
            $success = 'User added successfully!';
        }
    } else {
        $error = 'Please fill in all required fields (username, email, password).';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user_id'])) {
    $uid = intval($_POST['edit_user_id']);
    $uname = trim($_POST['edit_username']);
    $name = trim($_POST['edit_name']);
    $address = trim($_POST['edit_address']);
    $phone = trim($_POST['edit_phone']);
    $email = trim($_POST['edit_email']);
    $role = $_POST['edit_role'];
    if ($uid > 0 && $uname && $email) {
        $stmt = $pdo->prepare("UPDATE users SET username=?, name=?, address=?, phone=?, email=?, role=? WHERE id=?");
        $stmt->execute([$uname, $name, $address, $phone, $email, $role, $uid]);
        $success = 'User updated!';
    }
}

$user_stmt = $pdo->query("SELECT * FROM users");
$users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/modern-theme.css">
<div class="admin-container" style="max-width:900px;margin:40px auto;background:#fff;padding:32px;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,0.10);">
    <h1 style="margin-bottom:32px;font-size:2.2rem;color:#007bff;text-align:center;letter-spacing:1px;">Manage Users</h1>
    <a href="admin.php" style="color:#007bff;font-size:1.1rem;margin-bottom:24px;display:inline-block;"><i class="fas fa-arrow-left"></i> Back to Admin Dashboard</a>
    <div style="margin:32px 0 24px 0;padding:32px;background:#f8fafc;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);max-width:600px;margin-left:auto;margin-right:auto;">
        <h2 style="margin-bottom:18px;font-size:1.3rem;color:#222;">Add User</h2>
        <?php if ($success): ?><div style="color:green;margin-bottom:10px;font-weight:500;"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div style="color:red;margin-bottom:10px;font-weight:500;"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST" style="display:grid;gap:16px;">
            <input type="text" name="add_username" placeholder="Username *" required style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
            <input type="text" name="add_name" placeholder="Name" style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
            <input type="text" name="add_address" placeholder="Address" style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
            <input type="text" name="add_phone" placeholder="Phone" style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
            <input type="email" name="add_email" placeholder="Email *" required style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
            <select name="add_role" required style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <input type="password" name="add_password" placeholder="Password *" required style="padding:12px;font-size:1rem;border-radius:8px;border:1px solid #e3e8ee;">
            <button type="submit" name="add_user" style="background:#007bff;color:#fff;padding:12px 0;border:none;border-radius:8px;font-size:1.1rem;font-weight:500;box-shadow:0 2px 8px rgba(0,123,255,0.08);transition:background 0.2s;">Add User</button>
        </form>
    </div>
    <div style="overflow-x:auto;margin-top:32px;">
        <table class="admin-table" style="margin-top:0;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <tr style="background:#007bff;color:#fff;font-size:1rem;">
                <th>ID</th><th>Username</th><th>Name</th><th>Address</th><th>Phone</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
            <?php foreach ($users as $u): ?>
                <tr style="background:#fff;transition:box-shadow 0.2s;">
                    <td><?php echo $u['id']; ?></td>
                    <?php if (isset($_GET['edit']) && $_GET['edit'] == $u['id']): ?>
                        <form method="POST" style="display:contents;">
                            <td><input type="text" name="edit_username" value="<?php echo htmlspecialchars($u['username']); ?>" required style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td><input type="text" name="edit_name" value="<?php echo htmlspecialchars($u['name']); ?>" placeholder="Name" style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td><input type="text" name="edit_address" value="<?php echo htmlspecialchars($u['address']); ?>" placeholder="Address" style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td><input type="text" name="edit_phone" value="<?php echo htmlspecialchars($u['phone']); ?>" placeholder="Phone" style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td><input type="email" name="edit_email" value="<?php echo htmlspecialchars($u['email']); ?>" required placeholder="Email" style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;"></td>
                            <td>
                                <select name="edit_role" style="padding:8px;font-size:1rem;border-radius:6px;border:1px solid #e3e8ee;">
                                    <option value="user" <?php echo $u['role']==='user'?'selected':''; ?>>User</option>
                                    <option value="admin" <?php echo $u['role']==='admin'?'selected':''; ?>>Admin</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="edit_user_id" value="<?php echo $u['id']; ?>">
                                <button type="submit" style="background:#007bff;color:#fff;padding:8px 16px;border:none;border-radius:6px;font-size:1rem;font-weight:500;box-shadow:0 2px 8px rgba(0,123,255,0.08);transition:background 0.2s;">Save</button>
                                <a href="manage_users.php" style="padding:8px 16px;border-radius:6px;font-size:1rem;color:#222;background:#f5f7fa;margin-left:8px;text-decoration:none;">Cancel</a>
                            </td>
                        </form>
                    <?php else: ?>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['address']); ?></td>
                        <td><?php echo htmlspecialchars($u['phone']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td>
                            <a href="manage_users.php?edit=<?php echo $u['id']; ?>" style="color:#007bff;padding:8px 16px;border-radius:6px;font-size:1rem;background:#f5f7fa;margin-right:8px;text-decoration:none;transition:background 0.2s;">Edit</a>
                            <a href="delete_user.php?id=<?php echo $u['id']; ?>" class="remove-btn" onclick="return confirm('Delete this user?')" style="background:#dc3545;color:#fff;padding:8px 16px;border-radius:6px;font-size:1rem;text-decoration:none;transition:background 0.2s;">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
