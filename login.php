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

// معالجة تسجيل الدخول
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // استعلام للتحقق من وجود المستخدم
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // التحقق من وجود المستخدم
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // التحقق من كلمة المرور
        if (password_verify($password, $hashed_password)) {
            echo "<script>
                alert('تسجيل الدخول ناجح! مرحبًا بك, استمتع بتصفح الموقع.');
                window.location.href = 'index.html';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('كلمة المرور غير صحيحة.');
            </script>";
        }
    } else {
        echo "<script>
            alert('المستخدم غير موجود.');
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>