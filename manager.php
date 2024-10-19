<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: dangNhap.php");
    exit();
}
include './PHP/dungChung.php'; // Đường dẫn đến file chứa hàm checkUserRole
checkUserRole('manager.php');
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
        <section id="leaveApproval" class="content-section d-none">
            <h2>Duyệt Nghỉ Phép</h2>
            <form>
                <div class="mb-3">
                    <label for="leaveRequestId" class="form-label">Mã Yêu Cầu Nghỉ Phép</label>
                    <input type="text" id="leaveRequestId" name="leaveRequestId" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="leaveStatus" class="form-label">Trạng Thái</label>
                    <select id="leaveStatus" name="leaveStatus" class="form-select">
                        <option value="approved">Duyệt</option>
                        <option value="rejected">Từ Chối</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
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

    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin Script -->
    <script src="./Script/manager.js"></script>
</body>
</html>
