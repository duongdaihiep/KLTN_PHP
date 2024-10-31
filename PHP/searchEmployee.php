<?php
header('Content-Type: text/html; charset=utf-8');

include 'db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem có đang gửi yêu cầu tìm kiếm hay cập nhật
    if (isset($_POST['employeeIdInfo']) && !empty($_POST['employeeIdInfo'])) {
        // Nếu có `employeeIdInfo`, thực hiện tìm kiếm
        $employeeId = (int)$_POST['employeeIdInfo'];
        
        // Truy vấn để lấy thông tin nhân viên
        $sql = "SELECT EmployeeID, FirstName, LastName, Email, Phone, Position, HireDate, DepartmentID 
                FROM employees 
                WHERE EmployeeID = $employeeId";
        $result = mysql_query($sql, $conn);

        if ($result && mysql_num_rows($result) > 0) {
            $row = mysql_fetch_assoc($result);
            echo "<form method='POST' action='./PHP/searchEmployee.php'>"; // Gửi dữ liệu lại vào file hiện tại để xử lý cập nhật
            echo "<div class='mb-3'>
                    <label for='firstName' class='form-label'>Tên</label>
                    <input type='text' id='firstName' name='firstName' class='form-control' value='{$row['FirstName']}' required>
                  </div>";
            echo "<div class='mb-3'>
                    <label for='lastName' class='form-label'>Họ</label>
                    <input type='text' id='lastName' name='lastName' class='form-control' value='{$row['LastName']}' required>
                  </div>";
            echo "<div class='mb-3'>
                    <label for='email' class='form-label'>Email</label>
                    <input type='email' id='email' name='email' class='form-control' value='{$row['Email']}'>
                  </div>";
            echo "<div class='mb-3'>
                    <label for='phone' class='form-label'>Điện Thoại</label>
                    <input type='text' id='phone' name='phone' class='form-control' value='{$row['Phone']}'>
                  </div>";
            echo "<div class='mb-3'>
                    <label for='position' class='form-label'>Chức Vụ</label>
                    <input type='text' id='position' name='position' class='form-control' value='{$row['Position']}'>
                  </div>";
            echo "<div class='mb-3'>
                    <label for='hireDate' class='form-label'>Ngày Tuyển Dụng</label>
                    <input type='date' id='hireDate' name='hireDate' class='form-control' value='{$row['HireDate']}'>
                  </div>";
            echo "<input type='hidden' name='employeeId' value='{$row['EmployeeID']}'>"; // Để gửi lại mã nhân viên
            echo "<button type='submit' name='updateEmployee' class='btn btn-success'>Cập Nhật Thông Tin</button>";
            echo '</form>';
        } else {
            echo "<p class='text-danger'>Không tìm thấy nhân viên với mã này.</p>";
        }
    } elseif (isset($_POST['updateEmployee'])) {
        // Xử lý cập nhật thông tin nhân viên khi nhấn nút Cập Nhật
        $employeeId = (int)$_POST['employeeId'];
        $firstName = mysql_real_escape_string($_POST['firstName']);
        $lastName = mysql_real_escape_string($_POST['lastName']);
        $email = mysql_real_escape_string($_POST['email']);
        $phone = mysql_real_escape_string($_POST['phone']);
        $position = mysql_real_escape_string($_POST['position']);
        $hireDate = mysql_real_escape_string($_POST['hireDate']);
        $departmentId = !empty($_POST['departmentId']) ? (int)$_POST['departmentId'] : 'NULL';

        $updateSql = "UPDATE employees 
                      SET FirstName = '$firstName', LastName = '$lastName', Email = '$email', 
                          Phone = '$phone', Position = '$position', HireDate = '$hireDate'
                      WHERE EmployeeID = $employeeId";
        
        if (mysql_query($updateSql, $conn)) {
            echo "<form id='redirectForm' action='../admin.php' method='POST'>
            <input type='hidden' name='status' value='Thông tin nhân viên đã được cập nhật thành công!'>
          </form>
          <script>
            document.getElementById('redirectForm').submit();
          </script>";
        } else {
            echo "<form id='redirectForm' action='../admin.php' method='POST'>
            <input type='hidden' name='status' value='Lỗi khi cập nhật thông tin nhân viên!'>
          </form>
          <script>
            document.getElementById('redirectForm').submit();
          </script>";
        
    }

    mysql_close($conn);
    }
}
?>