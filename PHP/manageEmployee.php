<?php
include 'db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['searchEmployee']) && isset($_POST['employeeIdManage'])) {
        // Xử lý tìm kiếm
        $employeeId = mysql_real_escape_string($_POST['employeeIdManage']);
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
        $employeeId = mysql_real_escape_string($_POST['employeeId']);
        $action = $_POST['action'];

        $statusMessage = '';
        if ($action === 'lock') {
            $sql = "UPDATE user SET status = 'Lock' WHERE employeeId = '$employeeId'";
            $statusMessage = mysql_query($sql, $conn) ? 'Tài khoản đã được khóa thành công!' : 'Có lỗi xảy ra khi khóa tài khoản!';
        } elseif ($action === 'delete') {
            $sql1 = "DELETE FROM user WHERE employeeId = '$employeeId'";
            $sql2 = "DELETE FROM employees WHERE employeeId = '$employeeId'";
            $statusMessage = (mysql_query($sql1, $conn) && mysql_query($sql2, $conn)) ? 'Tài khoản đã được xóa thành công!' : 'Có lỗi xảy ra khi xóa tài khoản!';
        } elseif ($action === 'active') {
            $sql = "UPDATE user SET status = 'active' WHERE employeeId = '$employeeId'";
            $statusMessage = mysql_query($sql, $conn) ? 'Tài khoản đã được kích hoạt thành công!' : 'Có lỗi xảy ra khi kích hoạt tài khoản!';
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '../admin.php';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'status';
                    input.value = \"$statusMessage\";
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                });
              </script>";
        exit;
    }
}

mysql_close($conn);
?>
