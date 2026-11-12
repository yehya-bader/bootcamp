<?php
header('Content-Type: text/html; charset=utf-8');

try {
    $pdo = new PDO('sqlite::memory:', null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $pdo->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT,
        password TEXT
    )");

    $pdo->exec("INSERT INTO users (username, password) VALUES ('admin', 'admin_secret_123')");
    $pdo->exec("INSERT INTO users (username, password) VALUES ('ali', 'ali_pass_456')");
    $pdo->exec("INSERT INTO users (username, password) VALUES ('mahmoud', 'mahmoud_pass_789')");

} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات.");
}

$input_username = $_GET['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Test</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .box { padding: 15px; margin-bottom: 20px; border: 1px solid #ccc; background: #f9f9f9; }
        .danger { border-color: #dc3545; background: #f8d7da; }
        .success { border-color: #28a745; background: #d4edda; }
        pre { background: #fff; padding: 10px; border: 1px solid #ddd; direction: ltr; text-align: left; }
    </style>
</head>
<body>

    <h2>اختبار ثغرات SQL Injection</h2>
    <form method="GET">
        <label>أدخل اسم المستخدم </label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($input_username) ?>" style="width: 300px; padding: 5px;">
        <button type="submit">بحث</button>
    </form>

    <hr>

    <?php if ($input_username): ?>
        
        <!-- الطريقة غير الامنة -->
        <div class="box danger">
            <h3>1. الطريقة غير الآمنة (Direct Concatenation)</h3>
            <?php
            try {
                $unsafe_query = "SELECT * FROM users WHERE username = '$input_username'";
                echo "<p>الاستعلام المنفذ: <code>" . htmlspecialchars($unsafe_query) . "</code></p>";
                
                $unsafe_result = $pdo->query($unsafe_query)->fetchAll();
                echo "<pre>"; print_r($unsafe_result); echo "</pre>";
            } catch (PDOException $e) {
                echo "<p>حدث خطا في قاعدة البيانات أثناء تنفيذ الاستعلام.</p>";
            }
            ?>
        </div>

        <!-- الطريقة الامنة -->
        <div class="box success">
            <h3>2. الطريقة الآمنة (Prepared Statements)</h3>
            <?php
            try {
                $safe_query = "SELECT * FROM users WHERE username = :username";
                echo "<p>الاستعلام المنفذ: <code>SELECT * FROM users WHERE username = :username</code></p>";

                $stmt = $pdo->prepare($safe_query);
                $stmt->execute(['username' => $input_username]);
                $safe_result = $stmt->fetchAll();
                
                if (empty($safe_result)) {
                    echo "<p>لا توجد نتائج لم يتم العثور على مستخدم مطابق.</p>";
                } else {
                    echo "<pre>"; print_r($safe_result); echo "</pre>";
                }
            } catch (PDOException $e) {
                echo "<p>حدث خطا في قاعدة البيانات أثناء تنفيذ الاستعلام.</p>";
            }
            ?>
        </div>

    <?php endif; ?>

</body>
</html>