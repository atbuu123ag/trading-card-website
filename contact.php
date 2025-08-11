<?php
require_once 'includes/header.php';
?>
<div class="breadcrumb" style="margin: 16px 0 24px 0; font-size: 15px; color: #555;">
    <a href="index.php" style="color:#007bff;text-decoration:none;">Home</a> &rarr; <span>Contact Me</span>
</div>
<div class="main-content" style="max-width:700px;margin:0 auto;background:rgba(255,255,255,0.95);padding:32px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h1 style="font-size: 28px; margin-bottom: 18px; text-align:center; color:#007bff;">Contact Me</h1>
    <p style="font-size:16px;">If you have any questions, feedback, or business inquiries, please fill out the form below or reach out via email.</p>
    <form method="POST" style="margin-top:24px;">
        <input type="text" name="name" placeholder="Your Name" required style="width:100%;margin-bottom:8px;padding:8px;">
        <input type="email" name="email" placeholder="Your Email" required style="width:100%;margin-bottom:8px;padding:8px;">
        <textarea name="message" placeholder="Your Message" required style="width:100%;margin-bottom:8px;padding:8px;min-height:100px;"></textarea>
        <button type="submit" style="background:#007bff;color:#fff;padding:10px 20px;border:none;border-radius:4px;font-size:16px;">Send Message</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['message'])) {
        echo '<div style="color:green;margin-top:16px;">Thank you for contacting us! We will get back to you soon.</div>';
    }
    ?>
</div>
<?php require_once 'includes/footer.php'; ?>
<style>
    body {
        background: url('images/banner.png') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        position: relative;
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
