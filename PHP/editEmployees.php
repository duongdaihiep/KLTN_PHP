<?php
// Kết nối cơ sở dữ liệu
// header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');

include 'db.php';

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error]));
}

// Lấy dữ liệu từ yêu cầu POST
$employeeId = $_POST['employeeId'] ?? null;
$action = $_POST['action'] ?? null;

// Kiểm tra dữ liệu đầu vào
if (!$employeeId || !$action) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin yêu cầu."]);
    exit;
}

// Xử lý hành động
$status = '';
switch ($action) {
    case 'delete':
        // Xóa tài khoản
        $sql = "DELETE FROM user WHERE EmployeeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employeeId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Tài khoản đã được xóa."]);
        } else {
            echo json_encode(["success" => false, "message" => "Không thể xóa tài khoản: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'lock':
        // Khóa tài khoản
        $status = 'Locked';
        break;

    case 'active':
        // Kích hoạt tài khoản
        $status = 'Active';
        break;

    default:
        echo json_encode(["success" => false, "message" => "Hành động không hợp lệ."]);
        exit;
}

// Nếu hành động là "lock" hoặc "active", cập nhật trạng thái
if ($status) {
    $sql = "UPDATE user SET Status = ? WHERE EmployeeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $employeeId);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Trạng thái tài khoản đã được cập nhật thành '$status'."]);
    } else {
        echo json_encode(["success" => false, "message" => "Không thể cập nhật trạng thái: " . $stmt->error]);
    }
    $stmt->close();
}

// Đóng kết nối
$conn->close();


?>