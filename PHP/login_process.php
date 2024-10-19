<?php
session_start();
include 'db.php'; // Tệp kết nối đến cơ sở dữ liệu

// Nhận dữ liệu từ form đăng nhập
$username = $_POST['username'];
$password = $_POST['password'];

// Mã hóa mật khẩu bằng MD5 (phải khớp với cách mã hóa trong CSDL)
$hashedPassword = md5($password);

// Truy vấn tìm người dùng theo username (email hoặc số điện thoại)
$sql = "SELECT * FROM User WHERE Username = '" . mysql_real_escape_string($username) . "'";
$result = mysql_query($sql, $conn);

// Kiểm tra nếu tìm thấy tài khoản
if ($result && mysql_num_rows($result) > 0) {<?php
    session_start();
    include 'db.php'; // Tệp kết nối đến cơ sở dữ liệu
    
    // Nhận dữ liệu từ form đăng nhập
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Truy vấn tìm người dùng theo username (email hoặc số điện thoại)
    $sql = "SELECT * FROM User WHERE Username = '" . mysql_real_escape_string($username) . "'";
    $result = mysql_query($sql, $conn);
    
    // Kiểm tra nếu tìm thấy tài khoản
    if ($result && mysql_num_rows($result) > 0) {
        $row = mysql_fetch_assoc($result);
        
        // Lấy mật khẩu đã mã hóa từ CSDL
        $storedPasswordHash = $row['PasswordHash'];
    
        // Sử dụng password_verify để so sánh mật khẩu nhập và mật khẩu đã mã hóa
        if (password_verify($password, $storedPasswordHash)) {
            // Mật khẩu đúng, đăng nhập thành công
            $_SESSION['username'] = $username;
            header('Location: index.php'); // Chuyển hướng đến trang index.php
            exit();
        } else {
            // Mật khẩu sai, quay lại trang đăng nhập
            header('Location: ../dangNhap.php');
            exit();
        }
    } else {
        // Không tìm thấy người dùng, quay lại trang đăng nhập
        header('Location: ../dangNhap.php');
        exit();
    }
    
    mysql_close($conn);
    ?>
    
    $row = mysql_fetch_assoc($result);
    
    // Lấy mật khẩu đã mã hóa từ CSDL
    $storedPasswordHash = $row['PasswordHash'];

    // So sánh mật khẩu nhập và mật khẩu đã mã hóa
    if ($hashedPassword == $storedPasswordHash) {
        // Mật khẩu đúng, đăng nhập thành công
        $_SESSION['username'] = $username;
        header('Location: index.php'); // Chuyển hướng đến trang index.php
        exit();
    } else {
        // Mật khẩu sai
        header('Location: ../dangNhap.php');
        // echo "Mật khẩu không chính xác!";
    }
} else {
    // Không tìm thấy người dùng
    echo "Tài khoản không tồn tại!";
}

mysql_close($conn);
?>
