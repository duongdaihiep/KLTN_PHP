<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: dangNhap.php");
    exit();
}
include './PHP/dungChung.php'; // Đường dẫn đến file chứa hàm checkUserRole
checkUserRole('manager.php');
include './PHP/db.php';

// Xử lý yêu cầu duyệt nghỉ phép
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leaveRequestId']) && isset($_POST['leaveStatus'])) {
    $leaveRequestId = $_POST['leaveRequestId'];
    $leaveStatus = $_POST['leaveStatus'];

    // Cập nhật trạng thái yêu cầu nghỉ phép
    $sql_update = "UPDATE LeaveRequests SET Status = '$leaveStatus' WHERE LeaveRequestID = $leaveRequestId";
    if (mysql_query($sql_update, $conn)) {
        echo "<script>alert('Cập nhật trạng thái yêu cầu thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật trạng thái: " . mysql_error() . "');</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý - Hệ Thống Chấm Công</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/manager.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Quản Lý - Hệ Thống Chấm Công</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showSection('shiftScheduling')">Xếp Ca</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('attendanceApproval')">Duyệt Chấm Công</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('leaveApproval')">Duyệt Nghỉ Phép</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('overtimeApproval')">Duyệt Đăng Ký Tăng Ca</a>
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
        <!-- Xếp Ca -->
        <section id="shiftScheduling" class="content-section">
            <h2>Xếp Ca</h2>
            <form>
                <div class="mb-3">
                    <label for="employeeIdShift" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdShift" name="employeeIdShift" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="shiftDate" class="form-label">Ngày</label>
                    <input type="date" id="shiftDate" name="shiftDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="shiftTime" class="form-label">Ca Làm</label>
                    <select id="shiftTime" name="shiftTime" class="form-select">
                        <option value="morning">Sáng</option>
                        <option value="afternoon">Chiều</option>
                        <option value="night">Tối</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Xếp Ca</button>
            </form>
        </section>

        <!-- Duyệt Chấm Công -->
        <section id="attendanceApproval" class="content-section d-none">
            <h2>Duyệt Chấm Công</h2>
            <form>
                <div class="mb-3">
                    <label for="attendanceId" class="form-label">Mã Chấm Công</label>
                    <input type="text" id="attendanceId" name="attendanceId" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="attendanceStatus" class="form-label">Trạng Thái</label>
                    <select id="attendanceStatus" name="attendanceStatus" class="form-select">
                        <option value="approved">Duyệt</option>
                        <option value="rejected">Từ Chối</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
        </section>

        <!-- Duyệt Nghỉ Phép -->
        <section id="leaveApproval" class="content-section">
            <h3>Danh Sách Yêu Cầu Nghỉ Phép</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã Yêu Cầu</th>
                        <th>Mã Nhân Viên</th>
                        <th>Ngày Bắt Đầu</th>
                        <th>Ngày Kết Thúc</th>
                        <th>Lý Do</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM LeaveRequests ORDER BY CASE WHEN Status = 'pending' THEN 0 ELSE 1 END, LeaveRequestID DESC";
                    $result = mysql_query($sql, $conn);
                    // Hiển thị danh sách yêu cầu
                    if (mysql_num_rows($result) > 0) {
                        while ($row = mysql_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['LeaveRequestID'] . "</td>";
                            echo "<td>" . $row['EmployeeID'] . "</td>";
                            echo "<td>" . $row['StartDate'] . "</td>";
                            echo "<td>" . $row['EndDate'] . "</td>";
                            echo "<td>" . $row['Reason'] . "</td>";
                            echo "<td>" . $row['Status'] . "</td>";
                            echo 
                                "<td>
                                    <form method='POST' action='./PHP/leaveApproval.php' style='display:inline;'>
                                        <input type='hidden' name='leaveRequestId' value='" . $row['LeaveRequestID'] . "'>
                                        <input type='hidden' name='leaveStatus' value='approved'>
                                        <button type='submit' class='btn btn-success'>Duyệt</button>
                                    </form>
                                    <form method='POST' action='./PHP/leaveApproval.php' style='display:inline;'>
                                        <input type='hidden' name='leaveRequestId' value='" . $row['LeaveRequestID'] . "'>
                                        <input type='hidden' name='leaveStatus' value='Refuse'>
                                        <button type='submit' class='btn btn-danger'>Từ chối</button>
                                    </form>
                                </td>"  ;
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không có yêu cầu nào.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Duyệt Đăng Ký Tăng Ca -->
        <section id="overtimeApproval" class="content-section d-none">
            <h2>Duyệt Đăng Ký Tăng Ca</h2>
            <form>
                <div class="mb-3">
                    <label for="overtimeRequestId" class="form-label">Mã Đăng Ký Tăng Ca</label>
                    <input type="text" id="overtimeRequestId" name="overtimeRequestId" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="overtimeStatus" class="form-label">Trạng Thái</label>
                    <select id="overtimeStatus" name="overtimeStatus" class="form-select">
                        <option value="approved">Duyệt</option>
                        <option value="rejected">Từ Chối</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
        </section>

        <!-- Đăng Xuất -->
        <section id="logout" class="content-section d-none">
            <h2>Đăng Xuất</h2>
            <p>Bạn có chắc chắn muốn đăng xuất?</p>
            <button class="btn btn-warning">Đăng Xuất</button>
        </section>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
        echo "<script>alert(" . json_encode($_POST['status']) . ");</script>";
    }
    ?>
    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin Script -->
    <script src="./Script/manager.js"></script>
</body>
</html>
