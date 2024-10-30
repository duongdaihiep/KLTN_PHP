<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra phương thức gửi dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = $_POST['employeeIdManage'];
    $action = $_POST['action'];

    // Kiểm tra hành động là khóa hay xóa
    if ($action === 'lock') {
        // Cập nhật status trong bảng user
        $sql = "UPDATE user SET status = 'Lock' WHERE employeeId = '$employeeId'";
        if (mysql_query($sql, $conn)) {
            echo "<form id='redirectForm' action='../admin.php' method='POST'>
                <input type='hidden' name='status' value='Tài khoản đã được khóa thành công!'>
              </form>
              <script>
                document.getElementById('redirectForm').submit();
              </script>";

        } else {
            echo "<form id='redirectForm' action='../admin.php' method='POST'>
                <input type='hidden' name='status' value='Có lỗi xảy ra khi khóa tài khoản!'>
              </form>
              <script>
                document.getElementById('redirectForm').submit();
              </script>";
        }
    } elseif ($action === 'delete') {
        // Xóa nhân viên trong cả hai bảng user và employees
        $sql1 = "DELETE FROM user WHERE employeeId = '$employeeId'";
        $sql2 = "DELETE FROM employees WHERE employeeId = '$employeeId'";

        // Thực hiện xóa
        if (mysql_query($sql1, $conn) && mysql_query($sql2, $conn)) {
            echo "<form id='redirectForm' action='../admin.php' method='POST'>
                <input type='hidden' name='status' value='Tài khoản đã được xóa thành công! '>
              </form>
              <script>
                document.getElementById('redirectForm').submit();
              </script>";
        } else {
            // echo "<script>alert('Có lỗi xảy ra khi xóa tài khoản: " . mysql_error() . "'); window.location.href='../admin.php';</script>";

            echo "<form id='redirectForm' action='../admin.php' method='POST'>
                <input type='hidden' name='status' value='Có lỗi xảy ra khi khóa tài khoản! '>
              </form>
              <script>
                document.getElementById('redirectForm').submit();
              </script>";
        }
    }
}

// Đóng kết nối
mysql_close($conn);
?>
