<?php
// Thông tin kết nối MySQL
$servername = "localhost";   // Tên máy chủ MySQL
$username = "duong";         // Tên đăng nhập MySQL
$password = "123456";        // Mật khẩu MySQL
$dbname = "kltn";            // Tên cơ sở dữ liệu

// Kết nối MySQL
$conn = mysql_connect($servername, $username, $password);
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
