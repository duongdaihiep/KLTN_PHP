<?php
session_start();
include 'db.php'; // Tệp kết nối đến cơ sở dữ liệu

// Nhận dữ liệu từ form đăng nhập
$username = $_POST['username'];
$password = $_POST['password'];

// Mã hóa mật khẩu bằng MD5 (phải khớp với cách mã hóa trong CSDL)
$hashedPassword = md5($password);

// Truy vấn tìm người dùng theo username (email hoặc số điện thoại)
// Dùng mysql_real_escape_string để tránh SQL Injection
$sql = "SELECT * FROM User WHERE Username = '" . mysql_real_escape_string($username) . "'";
$result = mysql_query($sql, $conn);

// Kiểm tra nếu tìm thấy tài khoản
if ($result && mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);

    // Lấy mật khẩu đã mã hóa từ CSDL
    $storedPasswordHash = $row['PasswordHash'];

    // So sánh mật khẩu nhập và mật khẩu đã mã hóa
    if ($hashedPassword == $storedPasswordHash) {
        // Mật khẩu đúng, đăng nhập thành công
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['Role']; // Lưu role vào session
        
        // Kiểm tra role của người dùng
        if ($row['Role'] == 'admin') {
            // Chuyển hướng đến trang admin.php
            header('Location: ../admin.php');
        } elseif ($row['Role'] == 'staff') {
            // Chuyển hướng đến trang index.php
            header('Location: ../index.php');
        } elseif ($row['Role'] == 'manager') {
            // Chuyển hướng đến trang manager.php
            header('Location: ../manager.php');
        } else {
            // Nếu role không hợp lệ, có thể điều hướng về trang đăng nhập
            header('Location: ../dangNhap.php');
        }
        exit();
    } else {
        // Mật khẩu sai, quay lại trang đăng nhập
        echo "<script>alert('Mật khẩu không chính xác!')</script>";
        header('Location: ../dangNhap.php');
        exit();
    }
    
} else {
    // Không tìm thấy người dùng
    echo "<script>alert('Tài khoản không tồn tại!')</script>";
}

mysql_close($conn);
?>
