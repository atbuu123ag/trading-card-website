<?php
require_once 'includes/header.php';
?>
<div class="breadcrumb" style="margin: 16px 0 24px 0; font-size: 15px; color: #555;">
    <a href="index.php" style="color:#007bff;text-decoration:none;">Home</a> &rarr; <span>About Us</span>
</div>
<div class="main-content" style="max-width:700px;margin:0 auto;background:rgba(255,255,255,0.95);padding:32px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h1 style="font-size: 28px; margin-bottom: 18px; text-align:center; color:#007bff;">About Us</h1>
    <p style="font-size:16px;">Welcome to ABC Shop! We are passionate about trading cards and collectibles. Our mission is to provide the best selection of Yu-Gi-Oh, Digimon, Pokemon, and more, with excellent customer service and a secure shopping experience.</p>
    <p style="font-size:16px;">Whether you are a collector, player, or just starting out, we hope you find something special in our shop. Thank you for visiting and supporting us!</p>
    <p style="font-size:16px;">For any questions, please visit our <a href="contact.php" style="color:#007bff;">Contact Me</a> page.</p>
</div>
<?php require_once 'includes/footer.php'; ?>
<style>
    body {
        background: url('images/banner.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        
    }
    footer {
    width: 100%;
    position: absolute;
    left: 0;
    bottom: 0;
    background: #222;
    color: #fff;
    padding: 24px 0 12px 0;
    text-align: center;
    z-index: 10;
}
    .main-content {
        background: rgba(255,255,255,0.95);
        min-height: 400px;
        min-height: calc(100vh - 120px);
    }
    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb span {
        color: #555;
    }
</style>
