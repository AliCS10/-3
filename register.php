<?php
$servername = "localhost";
$username = "root"; // اسم المستخدم الافتراضي
$password = ""; // كلمة المرور الافتراضية
$dbname = "user_auth"; // اسم قاعدة البيانات

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// تحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معالجة تسجيل الحساب
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // إدخال البيانات في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
            alert('تم تسجيل الحساب بنجاح! مرحبًا بك في عائلتنا.');
            window.location.href = 'تسجيل الدخول.html';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('حدث خطأ أثناء التسجيل: " . addslashes($stmt->error) . "');
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>