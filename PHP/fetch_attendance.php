<?php
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employeeId']) && !empty($_POST['employeeId'])) {
//     $employeeId = $_POST['employeeId'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employeeIdAttendance']) && !empty($_POST['employeeIdAttendance'])) {
    $employeeId = $_POST['employeeIdAttendance'];
    

    include 'db.php';

    $sql = "SELECT attendanceID, EmployeeID, date, checkintime, checkouttime, 
            statuscheckin, statuscheckout 
            FROM attendance 
            WHERE EmployeeID = '$employeeId'";
    
    $result = mysql_query($sql, $conn);

    if ($result && mysql_num_rows($result) > 0) {
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
        
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr data-attendance-id='{$row['attendanceID']}'>
                    <td><span class='attendanceID'>{$row['attendanceID']}</span></td>
                    <td><span class='employeeID'>{$row['EmployeeID']}</span></td>
                    <td><span class='date'>{$row['date']}</span></td>
                    <td><span class='checkIn'>{$row['checkintime']}</span></td>
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
        echo "</tbody>";
    } else {
        echo "<tbody><tr><td colspan='8'>Không có dữ liệu cho nhân viên này.</td></tr></tbody>";
    }
    mysql_close($conn);
}
?>
