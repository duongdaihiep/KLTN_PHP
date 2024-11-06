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
// Kiểm tra và hiển thị thông báo
if (isset($_POST['status'])) {
    $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8');
    echo "<script>alert('$status');</script>";
}
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

    <div class="container mt-5 d-flex flex-column align-items-center">
        <!-- Sửa Chấm Công -->
        <section id="editAttendance" class="content-section">
            <div class="mb-3">
                <h2 class='mb-3 text-center'>Sửa Chấm Công</h2>

            </div>
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
                        // mysql_close($conn);
                    }
                    ?>
                </tbody>
            </table>


        </section>


        <!-- Sửa Thông Tin Nhân Viên -->
        <section id="editEmployeeInfo"  class="content-section d-none">
            <div class="mb-3">
                <h2 class='mb-3 text-center'>Sửa Thông Tin Nhân Viên</h2>

            </div>
            <!-- Hiển thị thông tin nhân viên -->
            <form method="POST" action="#editEmployeeInfo">
                <div class="mb-3">
                    <label for="employeeIdInfo" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdInfo" name="employeeIdInfo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" onclick="showSection('editEmployeeInfo')">Tìm Kiếm</button>
            </form>

            
        </section>



        <!-- Đăng Ký Tài Khoản Nhân Viên -->
        <section id="registerEmployee" class="content-section d-none">
        <div class="mb-3">
        <h2 class='mb-3 text-center'>Đăng Ký Tài Khoản Nhân Viên</h2>
        </div>
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
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" class="form-select">
                        <option value="staff">Staff</option>
                        <option value="manager">Manager</option>
                    </select>
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


        <!-- xóa, khóa tài khoản nhân viên  -->
        <section id="manageEmployeeAccount" class="content-section ">
            <div class="mb-3">
            <h2 class="mb-3 text-center">Xóa/Khóa Tài Khoản Nhân Viên</h2>

            </div>
            <!-- Form tìm kiếm nhân viên -->
            <form id="searchEmployeeForm">
                <div class="mb-3">
                    <label for="employeeIdManage" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdManage" name="employeeIdManage" class="form-control" required>
                    <button type="button" id="searchEmployeeButton" class="btn btn-primary mt-2" name="searchEmployee">Tìm Kiếm</button>
                </div>
            </form>

            <!-- Kết quả tìm kiếm -->
            <div id="employeeInfo" class="mb-3"></div>


            <!-- Form xóa/khóa tài khoản (ẩn ban đầu) -->
            <form id="manageAccountForm" action="./PHP/manageEmployee.php" method="POST" class="mb-3">
                <div class="mb-3">
                <input type="hidden" name="employeeId" id="hiddenEmployeeId">
                <div class="mb-3">
                    <label for="action" class="form-label">Hành Động</label>
                    <select id="action" name="action" class="form-select" required>
                        <option value="delete">Xóa Tài Khoản</option>
                        <option value="lock">Khóa Tài Khoản</option>
                        <option value="active">Kích hoạt tài Khoản</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Thực Hiện</button>
                </div>
            </form>
        </section>

        <script>
        document.getElementById('searchEmployeeButton').addEventListener('click', function() {
            const employeeId = document.getElementById('employeeIdManage').value;

            if (employeeId) {
                fetch('./PHP/search.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        'searchEmployee': 'true',
                        'employeeIdManage': employeeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const employeeInfoDiv = document.getElementById('employeeInfo');
                    const manageAccountForm = document.getElementById('manageAccountForm');
                    
                    if (data.status === 'found') {
                        const { EmployeeID, FullName, Email, Phone, status1 } = data.data;
                        employeeInfoDiv.innerHTML = `
                            <p><strong>Mã Nhân Viên:</strong> ${EmployeeID}</p>
                            <p><strong>Họ Tên:</strong> ${FullName}</p>
                            <p><strong>Email:</strong> ${Email}</p>
                            <p><strong>Điện Thoại:</strong> ${Phone}</p>
                            <p><strong>Trạng thái:</strong> ${status1}</p>
                        `;
                        
                        // Đặt ID nhân viên ẩn trong form và hiển thị form xóa/khóa tài khoản
                        document.getElementById('hiddenEmployeeId').value = EmployeeID;
                        manageAccountForm.classList.remove('d-none');
                    } else {
                        employeeInfoDiv.innerHTML = "<p class='text-danger'>Không tìm thấy nhân viên với mã này.</p>";
                        manageAccountForm.classList.add('d-none'); // Ẩn form nếu không tìm thấy
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
        </script>





        <!-- Đăng Xuất -->
        <section id="logout" class="content-section d-none">
            <h2>Đăng Xuất</h2>
            <p>Bạn có chắc chắn muốn đăng xuất?</p>
            <button class="btn btn-warning">Đăng Xuất</button>
        </section>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
        // Sử dụng json_encode để đảm bảo nội dung là an toàn cho JavaScript
        echo "<script>
                alert(" . json_encode($_POST['status']) . ");
            </script>";
    }
    ?>




<!-- <script>alert('$_POST[]')</script> -->
    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin Script -->
    <script src="./Script/admin.js"></script>
</body>
</html>
