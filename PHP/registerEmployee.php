<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php'; // Tệp kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ biểu mẫu
$username = $_POST['username'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['employeeEmail'];
$phone = $_POST['employeePhone'];
$position = $_POST['employeePosition'];
$hireDate = $_POST['hireDate'];
$passwordHash = md5($_POST['employeePassword']); // Mã hóa mật khẩu

// Thêm nhân viên vào bảng Employee
$sql_employee = $sql_employee = "INSERT INTO Employees (FirstName, LastName, Email, Phone, Position, HireDate) 
VALUES ('$firstName', '$lastName', '$email', '$phone', '$position', '$hireDate')";

if (mysql_query($sql_employee, $conn)) {
    // Lấy EmployeeID vừa tạo
    $employeeID = mysql_insert_id($conn);

    // Thêm người dùng vào bảng User
    $sql_user = "INSERT INTO User (Username, PasswordHash, Role, EmployeeID, status) VALUES ('$username', '$passwordHash', 'employee', $employeeID, 'active')";
    if (mysql_query($sql_user, $conn)) {
        echo "<script>alert('Đăng ký thành công!)</script>";
        header('Location: ../admin.php');
    } else {
        // echo "Lỗi: " . $sql_user . "<br>" . mysql_error($conn);
        echo "<script>alert('xảy ra lỗi!)</script>";
        header('Location: ../admin.php');
    }
} else {
    // echo "Lỗi: " . $sql_employee . "<br>" . mysql_error($conn);
    echo "<script>alert('xảy ra lỗi!)</script>";
    header('Location: ../admin.php');

}

// Đóng kết nối
mysql_close($conn);
?>
