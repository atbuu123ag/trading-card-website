
<?php
session_start();
session_destroy();
?>
<?php require_once 'includes/header.php'; ?>
<div class="main-content" style="min-height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div style="width:350px; background:rgba(255,255,255,0.97); border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,0.10); padding:32px 24px; text-align:center;">
        <h1 style="color:#007bff; margin-bottom:24px;">Logged Out</h1>
        <p style="font-size:16px; color:#555;">You have been successfully logged out.</p>
        <a href="index.php" style="display:inline-block; margin-top:20px; background:#007bff; color:#fff; padding:10px 24px; border-radius:5px; text-decoration:none; font-size:16px;">Return to Home</a>
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
</style>