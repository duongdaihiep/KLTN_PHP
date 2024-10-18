<?php
session_start();
include 'db.php'; // Tệp kết nối đến cơ sở dữ liệu

// Nhận dữ liệu từ form đăng nhập
$username = $_POST['username'];
$password = $_POST['password'];

// Truy vấn tìm người dùng theo username (email hoặc số điện thoại)
$sql = "SELECT * FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu tìm thấy tài khoản
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Lấy mật khẩu đã mã hóa từ CSDL
    $hashedPassword = $row['PasswordHash'];

    // Sử dụng password_verify để so sánh mật khẩu nhập và mật khẩu đã mã hóa
    if (password_verify($password, $hashedPassword)) {
        // Mật khẩu đúng, đăng nhập thành công
        $_SESSION['username'] = $username;
        header('Location: index.php'); // Chuyển hướng đến trang index.php
        exit();
    } else {
        // Mật khẩu sai
        echo "Mật khẩu không chính xác!";
    }
} else {
    // Không tìm thấy người dùng
    echo "Tài khoản không tồn tại!";
}

$stmt->close();
$conn->close();
?>
