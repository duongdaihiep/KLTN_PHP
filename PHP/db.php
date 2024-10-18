<?php
// Thông tin kết nối MySQL
$servername = "localhost";   // Tên máy chủ MySQL (localhost nếu chạy trên máy cục bộ)
$username = "duong";          // Tên đăng nhập MySQL (thường là 'root' cho máy cục bộ)
$password = "123456";              // Mật khẩu MySQL (để trống nếu không có mật khẩu)
$dbname = "kltn"; // Tên cơ sở dữ liệu bạn đã tạo

// Tạo kết nối MySQL
$conn = mysql_connect($servername, $username, $password);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysql_error());
}

// Chọn cơ sở dữ liệu
$db_selected = mysql_select_db($dbname, $conn);
if (!$db_selected) {
    die("Không thể chọn cơ sở dữ liệu: " . mysql_error());
}

// Thiết lập mã hóa utf8 để xử lý tiếng Việt
mysql_query("SET NAMES 'utf8'", $conn);
?>
