<?php
session_start();

// Hủy phiên làm việc
session_unset(); // Giải phóng tất cả các biến phiên
session_destroy(); // Hủy phiên

// Chuyển hướng người dùng về trang đăng nhập
header('Location: ../dangNhap.php');
exit();
?>
