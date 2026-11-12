<?php
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $max_size = 2 * 1024 * 1024; 
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "<p style='color:red;'>حدث خطأ أثناء رفع الملف.</p>";
    } elseif ($file['size'] > $max_size) {
        $message = "<p style='color:red;'>حجم الملف يتجاوز الحد المسموح (2 ميجابايت).</p>";
    } else {
        
        $allowed_extensions = ['jpg', 'png'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $message = "<p style='color:red;'>خطأ: الامتداد غير مسموح. (فقط JPG أو PNG)</p>";
        } else {
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            $allowed_mime_types = ['image/jpeg', 'image/png'];
            
            if (!in_array($mime_type, $allowed_mime_types)) {
                $message = "<p style='color:red;'>تحذير أمني: محتوى الملف الفعلي لا يتطابق مع امتداده!</p>";
            } else {
                
                $new_filename = bin2hex(random_bytes(16)) . '.' . $file_extension;
                $destination = $upload_dir . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    chmod($destination, 0644); 
                    $message = "<p style='color:green;'>تم رفع الملف بأمان. الاسم الجديد: <br><code>$new_filename</code></p>";
                } else {
                    $message = "<p style='color:red;'>حدث خطأ أثناء نقل الملف إلى المجلد النهائي.</p>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام الرفع الآمن</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .upload-container { border: 1px solid #ccc; padding: 20px; max-width: 500px; background: #f9f9f9; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>نظام رفع الملفات الآمن</h2>
    
    <?php echo $message; ?>
    
    <div class="upload-container">
        <form method="post" enctype="multipart/form-data">
            <label>اختر صورة (JPG, PNG):</label><br><br>
            <input type="file" name="uploaded_file" required><br><br>
            <button type="submit">رفع الملف</button>
        </form>
    </div>
</body>
</html>