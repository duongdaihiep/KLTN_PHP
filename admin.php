<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: dangNhap.php");
    exit();
}
include './PHP/dungChung.php'; // Đường dẫn đến file chứa hàm checkUserRole
checkUserRole('admin.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hệ Thống Chấm Công</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/adminStyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Quản Trị Viên - Hệ Thống Chấm Công</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="showSection('editAttendance')">Sửa Chấm Công</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showSection('editEmployeeInfo')">Sửa Thông Tin Nhân Viên</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showSection('registerEmployee')">Đăng Ký Tài Khoản Nhân Viên</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showSection('manageEmployeeAccount')">Xóa/Khóa Tài Khoản Nhân Viên</a>
                </li>
                <li class="nav-item">
                    <form action="./PHP/logout.php" method="post" style="display:inline;">
                        <button type="submit" class="btn btn-link nav-link" style="border: none; background: none; padding: 0;">
                            Đăng Xuất
                        </button>
                    </form>
                </li>
            </ul>

            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Sửa Chấm Công -->
        <section id="editAttendance" class="content-section">
            <h2>Sửa Chấm Công</h2>

            <!-- Form để tìm kiếm lịch sử chấm công -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="employeeId" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeId" name="employeeId" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Lịch Sử Chấm Công</button>
            </form>

            <!-- Bảng hiển thị lịch sử chấm công -->
            <table class="table mt-4" id="attendanceTable">
                
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employeeId']) && !empty($_POST['employeeId'])) {
                        $employeeId = $_POST['employeeId'];

                        // Truy vấn lấy lịch sử chấm công của nhân viên
                        $sql = "SELECT attendanceID, EmployeeID, date, checkintime, checkouttime, 
                                statuscheckin, statuscheckout 
                                FROM attendance 
                                WHERE EmployeeID = '$employeeId'";
                        include './PHP/db.php';
                        $result = mysql_query($sql, $conn);

                        echo "<thead>
                                <tr>
                                    <th>Attendance ID</th>
                                    <th>Employee ID</th>
                                    <th>Ngày</th>
                                    <th>Giờ Vào</th>
                                    <th>Trạng Thái Vào</th>
                                    <th>Giờ Ra</th>
                                    <th>Trạng Thái Ra</th>
                                    <th>Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>";

                        // Kiểm tra và hiển thị dữ liệu
                        if ($result && mysql_num_rows($result) > 0) {
                            while ($row = mysql_fetch_assoc($result)) {
                                echo "<tr data-attendance-id='{$row['attendanceID']}'>
                                        <td><span class='attendanceID'>{$row['attendanceID']}</span></td>
                                        <td><span class='employeeID'>{$row['EmployeeID']}</span></td>
                                        <td><span class='date'>{$row['date']}</span></td>
                                        <td><span class='checkIn'>{$row['checkintime']}</span></td>
                                        
                                        <!-- Dropdown cho trạng thái vào -->
                                        <td>
                                            <span class='statusCheckIn'>{$row['statuscheckin']}</span>
                                            <div class='dropdown d-inline'>
                                                <button class='btn btn-secondary dropdown-toggle' type='button' id='statusCheckInDropdown' data-bs-toggle='dropdown' aria-expanded='false'>
                                                    ▼
                                                </button>
                                                <ul class='dropdown-menu' aria-labelledby='statusCheckInDropdown'>
                                                    <li><a class='dropdown-item' href='#' data-value='late'>Late</a></li>
                                                    <li><a class='dropdown-item' href='#' data-value='approved'>Approved</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
                                        <td><span class='checkOut'>{$row['checkouttime']}</span></td>
                                        
                                        <!-- Dropdown cho trạng thái ra -->
                                        <td>
                                            <span class='statusCheckOut'>{$row['statuscheckout']}</span>
                                            <div class='dropdown d-inline'>
                                                <button class='btn btn-secondary dropdown-toggle' type='button' id='statusCheckInDropdown' data-bs-toggle='dropdown' aria-expanded='false'>
                                                    ▼
                                                </button>
                                                <ul class='dropdown-menu' aria-labelledby='statusCheckInDropdown'>
                                                    <li><a class='dropdown-item' href='#' data-value='soon'>Soon</a></li>
                                                    <li><a class='dropdown-item' href='#' data-value='approved'>Approved</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <button class='btn btn-success saveBtn'>Lưu</button>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Không có dữ liệu cho nhân viên này.</td></tr>";
                        }
                        mysql_close($conn);
                    }
                    ?>
                </tbody>
            </table>


        </section>


        <!-- Sửa Thông Tin Nhân Viên -->
        <section id="editEmployeeInfo"  class="content-section d-none">
            <h2>Sửa Thông Tin Nhân Viên</h2>
            <form method="POST" action="#editEmployeeInfo">
                <div class="mb-3">
                    <label for="employeeIdInfo" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdInfo" name="employeeIdInfo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" onclick="showSection('editEmployeeInfo')">Tìm Kiếm</button>
            </form>

            <!-- Hiển thị thông tin nhân viên -->
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employeeIdInfo']) && !empty($_POST['employeeIdInfo'])) {
                $employeeId = $_POST['employeeIdInfo'];

                // Truy vấn lấy thông tin nhân viên
                $sql = "SELECT EmployeeID, FirstName, LastName, Email, Phone, Position, HireDate, DepartmentID 
                        FROM employees 
                        WHERE EmployeeID = '$employeeId'";
                include './PHP/db.php'; // Kết nối cơ sở dữ liệu
                $result = mysql_query($sql, $conn);

                // Kiểm tra và hiển thị dữ liệu
                if ($result && mysql_num_rows($result) > 0) {
                    $row = mysql_fetch_assoc($result);
                    echo '<form method="POST" action="updateEmployee.php">'; // Đường dẫn đến file xử lý cập nhật
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
                    echo "<div class='mb-3'>
                            <label for='departmentId' class='form-label'>Mã Phòng Ban</label>
                            <input type='text' id='departmentId' name='departmentId' class='form-control' value='{$row['DepartmentID']}'>
                        </div>";
                    echo "<input type='hidden' name='employeeId' value='{$row['EmployeeID']}'>"; // Để gửi lại mã nhân viên
                    echo "<button type='submit' class='btn btn-success'>Cập Nhật Thông Tin</button>";
                    echo '</form>';
                } else {
                    echo "<p class='text-danger'>Không tìm thấy nhân viên với mã này.</p>";
                }
                mysql_close($conn);
            }
            ?>
        </section>



        <!-- Đăng Ký Tài Khoản Nhân Viên -->
        <section id="registerEmployee" class="content-section d-none">
            <h2>Đăng Ký Tài Khoản Nhân Viên</h2>
            <form method="POST" action="./PHP/registerEmployee.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="firstName" class="form-label">Tên</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Họ</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="employeeEmail" class="form-label">Email</label>
                    <input type="email" id="employeeEmail" name="employeeEmail" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="employeePhone" class="form-label">Điện Thoại</label>
                    <input type="text" id="employeePhone" name="employeePhone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="employeePosition" class="form-label">Chức Vụ</label>
                    <input type="text" id="employeePosition" name="employeePosition" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="hireDate" class="form-label">Ngày Tuyển Dụng</label>
                    <input type="date" id="hireDate" name="hireDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="employeePassword" class="form-label">Mật Khẩu</label>
                    <input type="password" id="employeePassword" name="employeePassword" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Đăng Ký</button>
            </form>
        </section>



        <!-- Xóa/Khóa Tài Khoản Nhân Viên -->
        <section id="manageEmployeeAccount" class="content-section d-none">
            <h2>Xóa/Khóa Tài Khoản Nhân Viên</h2>
            <form>
                <div class="mb-3">
                    <label for="employeeIdManage" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdManage" name="employeeIdManage" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="action" class="form-label">Hành Động</label>
                    <select id="action" name="action" class="form-select">
                        <option value="delete">Xóa Tài Khoản</option>
                        <option value="lock">Khóa Tài Khoản</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Thực Hiện</button>
            </form>
        </section>

        <!-- Đăng Xuất -->
        <section id="logout" class="content-section d-none">
            <h2>Đăng Xuất</h2>
            <p>Bạn có chắc chắn muốn đăng xuất?</p>
            <button class="btn btn-warning">Đăng Xuất</button>
        </section>
    </div>

    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin Script -->
    <script src="./Script/admin.js"></script>
</body>
</html>
