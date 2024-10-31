<?php
header('Content-Type: application/json; charset=utf-8');
include 'db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['searchEmployee']) && isset($_POST['employeeIdManage'])) {
        // Xử lý tìm kiếm
        $employeeId = $_POST['employeeIdManage'];
        $sql = "SELECT EmployeeID, CONCAT(FirstName, ' ', LastName) AS FullName, Email, Phone 
                FROM employees 
                WHERE EmployeeID = '$employeeId'";
        $result = mysql_query($sql, $conn);

        if ($result && mysql_num_rows($result) > 0) {
            $employee = mysql_fetch_assoc($result);
            echo json_encode(array('status' => 'found', 'data' => $employee));
        } else {
            echo json_encode(array('status' => 'not_found', 'message' => 'Không tìm thấy nhân viên'));
        }
        exit;
    }

    if (isset($_POST['employeeId']) && isset($_POST['action'])) {
        $employeeId = $_POST['employeeId'];
        $action = $_POST['action'];

        if ($action === 'lock') {
            $sql = "UPDATE user SET status = 'Lock' WHERE employeeId = '$employeeId'";
            if (mysql_query($sql, $conn)) {
                echo json_encode(array('status' => 'success', 'message' => 'Tài khoản đã được khóa thành công!'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Có lỗi xảy ra khi khóa tài khoản!'));
            }
        } elseif ($action === 'delete') {
            $sql1 = "DELETE FROM user WHERE employeeId = '$employeeId'";
            $sql2 = "DELETE FROM employees WHERE employeeId = '$employeeId'";
            if (mysql_query($sql1, $conn) && mysql_query($sql2, $conn)) {
                echo json_encode(array('status' => 'success', 'message' => 'Tài khoản đã được xóa thành công!'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Có lỗi xảy ra khi xóa tài khoản!'));
            }
        } elseif($action === 'active'){
            $sql="UPDATE user SET status = 'active' WHERE employeeId = '$employeeId'";
            if (mysql_query($sql, $conn)) {
                echo json_encode(array('status' => 'success', 'message' => 'Tài khoản đã được kích hoạt thành công!'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Có lỗi xảy ra khi kích hoạt tài khoản!'));
            }
        }
    }
}

mysql_close($conn);
?>
