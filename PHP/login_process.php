<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
include 'db.php'; // Tệp kết nối đến cơ sở dữ liệu

$username = $_POST['username'];
$password = $_POST['password'];
$hashedPassword = md5($password);

$sql = "SELECT * FROM User WHERE Username = '" . mysql_real_escape_string($username) . "'";
$result = mysql_query($sql, $conn);

if ($result && mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    $storedPasswordHash = $row['PasswordHash'];

    if ($hashedPassword == $storedPasswordHash) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['Role'];

        if ($row['Status'] == 'Lock') {
            session_destroy();
            echo json_encode(array('status' => 'error', 'message' => 'Tài khoản đã bị khóa!'));
        } else {
            switch ($row['Role']) {
                case 'admin':
                    echo json_encode(array('status' => 'success', 'redirect' => './admin.php'));
                    break;
                case 'staff':
                    echo json_encode(array('status' => 'success', 'redirect' => './index.php'));
                    break;
                case 'manager':
                    echo json_encode(array('status' => 'success', 'redirect' => './manager.php'));
                    break;
                default:
                    echo json_encode(array('status' => 'error', 'message' => 'Tài khoản không hợp lệ: Role!'));
                    break;
            }
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Mật khẩu không chính xác!'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Tài khoản không tồn tại!'));
}

mysql_close($conn);
?>
