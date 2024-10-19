<?php
session_start();

function checkUserRole($redirectPage) {
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['username'])) {
        header('Location: dangNhap.php'); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
        exit();
    }

    // Kiểm tra vai trò của người dùng
    $role = $_SESSION['role']; // Giả sử bạn đã lưu vai trò của người dùng trong session

    switch ($role) {
        case 'admin':
            // Nếu là admin, cho phép truy cập vào trang admin.php
            if ($redirectPage !== 'admin.php') {
                header('Location: admin.php'); // Chuyển hướng đến trang admin.php
                exit();
            }
            break;

        case 'manager':
            // Nếu là manager, cho phép truy cập vào trang manager.php
            if ($redirectPage !== 'manager.php') {
                header('Location: manager.php'); // Chuyển hướng đến trang manager.php
                exit();
            }
            break;

        case 'staff':
            // Nếu là staff, cho phép truy cập vào trang index.php
            if ($redirectPage !== 'index.php') {
                header('Location: index.php'); // Chuyển hướng đến trang index.php
                exit();
            }
            break;

        default:
            header('Location: dangNhap.php'); // Nếu không khớp với vai trò nào, quay lại trang đăng nhập
            exit();
    }
}
?>
