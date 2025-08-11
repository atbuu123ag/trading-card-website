
<?php
session_start();
require_once 'includes/header.php';

if (!isset($_SESSION['username'])) {
    echo '<p>Please <a href="login.php">login</a> to view and edit your profile.</p>';
require_once 'includes/footer.php';

    exit;
}

$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<p>User not found.</p>';
    require_once 'includes/footer.php';
    exit;
}

$edit_field = $_GET['edit'] ?? null;
$success = false;
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field']) && isset($_POST['value'])) {
    $field = $_POST['field'];
    $value = trim($_POST['value']);
    $allowed = ['name','address','phone','email'];
    if (in_array($field, $allowed)) {
        if ($value === '') {
            $error = ucfirst($field).' is required.';
        } else {
            $stmt = $pdo->prepare("UPDATE users SET $field = ? WHERE username = ?");
            $stmt->execute([$value, $username]);
            $user[$field] = $value;
            $success = true;
        }
    } else {
        $error = 'Invalid field.';
    }
}

?>

<div class="main-content" style="min-height: 100vh; display: flex; justify-content: center; align-items: center; background: rgba(255,255,255,0.1);">
    <div class="profile-card" style="width:420px; background:rgba(255,255,255,0.98); border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.12); padding:40px 32px;">
        <h1 style="color:#007bff; text-align:center; margin-bottom:32px; font-size:2rem; letter-spacing:1px;"><i class="fas fa-user-circle" style="margin-right:8px;"></i>Profile</h1>
        <?php if ($success): ?>
            <div style="color:green; text-align:center; margin-bottom:20px; font-size:16px;">Profile updated successfully!</div>
        <?php elseif ($error): ?>
            <div style="color:red; text-align:center; margin-bottom:20px; font-size:16px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <table style="width:100%;font-size:17px; border-collapse:separate; border-spacing:0 18px;">
            <tr>
                <td style="width:32%;font-weight:600; color:#34495e;"><i class="fas fa-user" style="margin-right:7px;color:#007bff;"></i>Name:</td>
                <td>
                    <?php if ($edit_field === 'name'): ?>
                        <form method="POST" style="display:flex;gap:10px;align-items:center;">
                            <input type="hidden" name="field" value="name">
                            <input type="text" name="value" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required style="padding:10px; border-radius:7px; border:1px solid #ccc; font-size:17px; width:70%;">
                            <button type="submit" style="background:#007bff;color:#fff;border:none;padding:8px 18px;border-radius:7px;font-size:16px;"><i class="fas fa-save"></i> Save</button>
                            <a href="profile.php" style="padding:8px 18px; color:#555; background:#eee; border-radius:7px; text-decoration:none; font-size:16px;">Cancel</a>
                        </form>
                    <?php else: ?>
                        <?php echo htmlspecialchars($user['name'] ?? ''); ?>
                        <a href="profile.php?edit=name" style="margin-left:16px;color:#007bff;font-weight:500;"><i class="fas fa-edit"></i> Edit</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight:600; color:#34495e;"><i class="fas fa-map-marker-alt" style="margin-right:7px;color:#007bff;"></i>Address:</td>
                <td>
                    <?php if ($edit_field === 'address'): ?>
                        <form method="POST" style="display:flex;gap:10px;align-items:center;">
                            <input type="hidden" name="field" value="address">
                            <input type="text" name="value" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required style="padding:10px; border-radius:7px; border:1px solid #ccc; font-size:17px; width:70%;">
                            <button type="submit" style="background:#007bff;color:#fff;border:none;padding:8px 18px;border-radius:7px;font-size:16px;"><i class="fas fa-save"></i> Save</button>
                            <a href="profile.php" style="padding:8px 18px; color:#555; background:#eee; border-radius:7px; text-decoration:none; font-size:16px;">Cancel</a>
                        </form>
                    <?php else: ?>
                        <?php echo htmlspecialchars($user['address'] ?? ''); ?>
                        <a href="profile.php?edit=address" style="margin-left:16px;color:#007bff;font-weight:500;"><i class="fas fa-edit"></i> Edit</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight:600; color:#34495e;"><i class="fas fa-phone" style="margin-right:7px;color:#007bff;"></i>Phone:</td>
                <td>
                    <?php if ($edit_field === 'phone'): ?>
                        <form method="POST" style="display:flex;gap:10px;align-items:center;">
                            <input type="hidden" name="field" value="phone">
                            <input type="text" name="value" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required style="padding:10px; border-radius:7px; border:1px solid #ccc; font-size:17px; width:70%;">
                            <button type="submit" style="background:#007bff;color:#fff;border:none;padding:8px 18px;border-radius:7px;font-size:16px;"><i class="fas fa-save"></i> Save</button>
                            <a href="profile.php" style="padding:8px 18px; color:#555; background:#eee; border-radius:7px; text-decoration:none; font-size:16px;">Cancel</a>
                        </form>
                    <?php else: ?>
                        <?php echo htmlspecialchars($user['phone'] ?? ''); ?>
                        <a href="profile.php?edit=phone" style="margin-left:16px;color:#007bff;font-weight:500;"><i class="fas fa-edit"></i> Edit</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight:600; color:#34495e;"><i class="fas fa-envelope" style="margin-right:7px;color:#007bff;"></i>Email:</td>
                <td>
                    <?php if ($edit_field === 'email'): ?>
                        <form method="POST" style="display:flex;gap:10px;align-items:center;">
                            <input type="hidden" name="field" value="email">
                            <input type="email" name="value" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required style="padding:10px; border-radius:7px; border:1px solid #ccc; font-size:17px; width:70%;">
                            <button type="submit" style="background:#007bff;color:#fff;border:none;padding:8px 18px;border-radius:7px;font-size:16px;"><i class="fas fa-save"></i> Save</button>
                            <a href="profile.php" style="padding:8px 18px; color:#555; background:#eee; border-radius:7px; text-decoration:none; font-size:16px;">Cancel</a>
                        </form>
                    <?php else: ?>
                        <?php echo htmlspecialchars($user['email'] ?? ''); ?>
                        <a href="profile.php?edit=email" style="margin-left:16px;color:#007bff;font-weight:500;"><i class="fas fa-edit"></i> Edit</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    body {
        background: url('images/banner.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .profile-card {
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .main-content {
        min-height: 100vh;
    }
    a:hover {
        text-decoration: underline;
    }
    input:focus {
        border-color: #007bff;
        outline: none;
    }
    button[type="submit"]:hover {
        background: #0056b3 !important;
    }
</style>
