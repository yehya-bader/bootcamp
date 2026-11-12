<?php
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

$message = "";
$old_session_id = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    session_regenerate_id(true);
    
    $_SESSION['logged_in'] = true;
    $_SESSION['user'] = 'admin';
    
    $new_session_id = session_id();
    $message = "Login Successful.<br>Old Session ID: <code>$old_session_id</code><br>New Session ID: <code>$new_session_id</code>";
}

setcookie('unprotected_cookie', 'This_is_Visible_to_JS', [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => false,
    'httponly' => false,
    'samesite' => 'Lax'
]);

setcookie('protected_cookie', 'Hidden_Secret_Data', [
    'expires' => time() + 3600,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الجلسات والكوكيز المحمية</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .box { padding: 15px; margin-bottom: 20px; border: 1px solid #ccc; background: #f9f9f9; }
        .result { margin-top: 15px; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; }
        button { padding: 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>اختبار إدارة الجلسات والكوكيز</h2>
    
    <?php if(!empty($message)) echo "<div class='box' style='border-color: #c3e6cb; background: #d4edda;'>$message</div>"; ?>
    
    <div class="box">
        <h3>1. تجديد معرف الجلسة (Session Fixation)</h3>
        <p>معرف الجلسة الحالي: <strong><?php echo session_id(); ?></strong></p>
        <form method="post">
            <button type="submit" name="login">محاكاة تسجيل الدخول (Regenerate ID)</button>
        </form>
    </div>

    <div class="box">
        <h3>2. حماية الكوكيز (HttpOnly & XSS Prevention)</h3>
        <button onclick="checkCookies()">قراءة الكوكيز بواسطة JavaScript</button>
        
        <div id="js_result" class="result">
            <!-- نتيجة الجافاسكريبت ستظهر هنا -->
        </div>
    </div>

    <script>
        function checkCookies() {
            var cookies = document.cookie;
            var displayElement = document.getElementById('js_result');
            
            if (cookies) {
                displayElement.innerHTML = "<strong>الكوكيز المقروءة:</strong><br><code style='color:red;'>" + cookies + "</code><br><br><em> الكوكيز المحمية (protected_cookie و PHPSESSID) لم تظهر بفضل خاصية HttpOnly</em>";
            } else {
                displayElement.innerHTML = "لم يستطع JavaScript قراءة أي كوكيز.";
            }
        }
    </script>
</body>
</html>