<?php
header('Content-Type: application/json; charset=utf-8');
include 'db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchEmployee']) && $_POST['searchEmployee'] === 'true') {
    $employeeId = $_POST['employeeIdManage'];
    
    // Truy vấn để lấy thông tin nhân viên dựa trên mã nhân viên
    $sql = "SELECT employees.EmployeeID, CONCAT(employees.FirstName, ' ', employees.LastName) AS FullName, employees.Email, employees.Phone, user.status as status1
        FROM employees 
        INNER JOIN user ON employees.EmployeeID = user.EmployeeID
        WHERE employees.EmployeeID = '$employeeId'";

    $result = mysql_query($sql, $conn);

    if ($result && mysql_num_rows($result) > 0) {
        $employee = mysql_fetch_assoc($result);
        echo json_encode(array('status' => 'found', 'data' => $employee));
    } else {
        echo json_encode(array('status' => 'not_found', 'message' => 'Không tìm thấy nhân viên'));
    }
    exit;
}

// Đóng kết nối
mysql_close($conn);
?>
