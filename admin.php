<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hệ Thống Chấm Công</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/adminStyle.css">
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
                        <a class="nav-link" href="#" onclick="showSection('logout')">Đăng Xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Sửa Chấm Công -->
        <section id="editAttendance" class="content-section">
            <h2>Sửa Chấm Công</h2>
            <form>
                <div class="mb-3">
                    <label for="employeeId" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeId" name="employeeId" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Ngày</label>
                    <input type="date" id="date" name="date" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="checkIn" class="form-label">Giờ Vào</label>
                    <input type="time" id="checkIn" name="checkIn" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="checkOut" class="form-label">Giờ Ra</label>
                    <input type="time" id="checkOut" name="checkOut" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
            </form>
        </section>

        <!-- Sửa Thông Tin Nhân Viên -->
        <section id="editEmployeeInfo" class="content-section d-none">
            <h2>Sửa Thông Tin Nhân Viên</h2>
            <form>
                <div class="mb-3">
                    <label for="employeeIdInfo" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdInfo" name="employeeIdInfo" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="employeeName" class="form-label">Tên Nhân Viên</label>
                    <input type="text" id="employeeName" name="employeeName" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="employeePosition" class="form-label">Chức Vụ</label>
                    <input type="text" id="employeePosition" name="employeePosition" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
        </section>

        <!-- Đăng Ký Tài Khoản Nhân Viên -->
        <section id="registerEmployee" class="content-section d-none">
            <h2>Đăng Ký Tài Khoản Nhân Viên</h2>
            <form>
                <div class="mb-3">
                    <label for="employeeNameReg" class="form-label">Tên Nhân Viên</label>
                    <input type="text" id="employeeNameReg" name="employeeNameReg" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="employeeEmail" class="form-label">Email</label>
                    <input type="email" id="employeeEmail" name="employeeEmail" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="employeePassword" class="form-label">Mật Khẩu</label>
                    <input type="password" id="employeePassword" name="employeePassword" class="form-control">
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
