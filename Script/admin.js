document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.editBtn');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');  // Lấy dòng hiện tại
            const saveButton = row.querySelector('.saveBtn');

            // Lấy giá trị từ các ô hiện tại
            const attendanceID = row.querySelector('.attendanceID').innerText; // ID không thay đổi
            const employeeID = row.querySelector('.employeeID').innerText; // ID không thay đổi
            const date = row.querySelector('.date').innerText;
            const checkIn = row.querySelector('.checkIn').innerText;
            const checkOut = row.querySelector('.checkOut').innerText;
            const statusCheckIn = row.querySelector('.statusCheckIn').innerText;
            const statusCheckOut = row.querySelector('.statusCheckOut').innerText;

            // Thay đổi các ô thành input để chỉnh sửa
            row.querySelector('.attendanceID').innerHTML = `<input type="text" value="${attendanceID}" disabled>`; // Không cho sửa
            row.querySelector('.employeeID').innerHTML = `<input type="text" value="${employeeID}" disabled>`; // Không cho sửa
            row.querySelector('.date').innerHTML = `<input type="text" value="${date}">`;
            row.querySelector('.checkIn').innerHTML = `<input type="text" value="${checkIn}">`;
            row.querySelector('.checkOut').innerHTML = `<input type="text" value="${checkOut}">`;
            row.querySelector('.statusCheckIn').innerHTML = `<input type="text" value="${statusCheckIn}">`;
            row.querySelector('.statusCheckOut').innerHTML = `<input type="text" value="${statusCheckOut}">`;

            // Hiển thị nút lưu và ẩn nút sửa
            this.style.display = 'none';
            saveButton.style.display = 'inline-block';

            // Xử lý khi người dùng nhấn nút Lưu
            saveButton.addEventListener('click', function() {
                const updatedDate = row.querySelector('.date input').value;
                const updatedCheckIn = row.querySelector('.checkIn input').value;
                const updatedCheckOut = row.querySelector('.checkOut input').value;
                const updatedStatusCheckIn = row.querySelector('.statusCheckIn input').value;
                const updatedStatusCheckOut = row.querySelector('.statusCheckOut input').value;

                // AJAX gửi dữ liệu đã chỉnh sửa đến server để cập nhật
                fetch('./PHP/update_attendance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        attendanceID: attendanceID, // ID không thay đổi
                        employeeID: employeeID, // ID không thay đổi (nếu cần sử dụng)
                        date: updatedDate,
                        checkIn: updatedCheckIn,
                        checkOut: updatedCheckOut,
                        statusCheckIn: updatedStatusCheckIn,
                        statusCheckOut: updatedStatusCheckOut
                    })
                })
                .then(response => {
                    // Kiểm tra nếu phản hồi không phải JSON
                    if (!response.ok) {
                        console.error('HTTP error:', response.status, response.statusText);
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Cập nhật lại các ô với giá trị mới sau khi lưu
                        row.querySelector('.date').innerText = updatedDate;
                        row.querySelector('.checkIn').innerText = updatedCheckIn;
                        row.querySelector('.checkOut').innerText = updatedCheckOut;
                        row.querySelector('.statusCheckIn').innerText = updatedStatusCheckIn;
                        row.querySelector('.statusCheckOut').innerText = updatedStatusCheckOut;

                        // Hiển thị lại nút sửa và ẩn nút lưu
                        saveButton.style.display = 'none';
                        button.style.display = 'inline-block';
                    } else {
                        console.error('Update failed:', data.message);
                        alert('Có lỗi xảy ra khi cập nhật dữ liệu: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error occurred:', error);
                    alert('Có lỗi xảy ra khi gửi yêu cầu. Chi tiết: ' + error.message);
                
                    // In ra phản hồi nếu có
                    fetch('./PHP/update_attendance.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            attendanceID: attendanceID, // ID không thay đổi
                            employeeID: employeeID, // ID không thay đổi (nếu cần sử dụng)
                            date: updatedDate,
                            checkIn: updatedCheckIn,
                            checkOut: updatedCheckOut,
                            statusCheckIn: updatedStatusCheckIn,
                            statusCheckOut: updatedStatusCheckOut
                        })
                    })
                    .then(response => response.text()) // Đọc phản hồi dưới dạng văn bản
                    .then(text => {
                        console.log('Response from server:', text); // In ra nội dung phản hồi
                    });
                });
                
                
            });
        });
    });
});
// Hàm để hiển thị các section
function showSection(sectionId) {
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.add('d-none'); // Ẩn tất cả các section
    });
    document.getElementById(sectionId).classList.remove('d-none'); // Hiện section được chọn
}

// Đảm bảo khi tải trang, section mặc định là "editAttendance"
document.addEventListener('DOMContentLoaded', function () {
    showSection('editAttendance'); // Thay đổi đây nếu bạn muốn hiển thị tab khác khi tải trang
});



// $(document).ready(function() {
//     // Xử lý khi người dùng chọn trạng thái từ dropdown
//     $('#attendanceTable').on('click', '.dropdown-item', function() {
//         var selectedStatus = $(this).text(); // Lấy giá trị đã chọn
//         var statusCell = $(this).closest('tr').find('.statusCheckIn'); // Tìm ô trạng thái
//         statusCell.text(selectedStatus); // Cập nhật ô trạng thái
//     });
// });



$(document).ready(function() {
    // Khi người dùng chọn trạng thái từ dropdown
    $('#attendanceTable').on('click', '.dropdown-item', function(event) {
        event.preventDefault();

        const selectedStatus = $(this).data('value');
        const statusCell = $(this).closest('td').find('span');
        
        // Cập nhật giá trị trạng thái trong ô
        statusCell.text(selectedStatus);
    });

    // Khi người dùng nhấn nút "Lưu"
    $('#attendanceTable').on('click', '.saveBtn', function() {
        const row = $(this).closest('tr');
        const attendanceID = row.data('attendance-id');
        const statusCheckIn = row.find('.statusCheckIn').text();
        const statusCheckOut = row.find('.statusCheckOut').text();

        // Gửi yêu cầu AJAX đến server để cập nhật dữ liệu
        $.ajax({
            url: './PHP/update_attendance_status.php', // URL của file PHP xử lý cập nhật
            method: 'POST',
            data: {
                attendanceID: attendanceID,
                statusCheckIn: statusCheckIn,
                statusCheckOut: statusCheckOut
            },
            success: function(response) {
                alert(response); // Hiển thị thông báo thành công
            },
            error: function(xhr, status, error) {
                alert("Lỗi khi cập nhật trạng thái: " + error); // Hiển thị lỗi nếu có
            }
        });
    });
});


document.querySelector('#editEmployeeInfo form').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngăn tải lại trang

    const formData = new FormData(this);

    fetch('./PHP/searchEmployee.php', { // Cập nhật URL đến file tìm kiếm
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.querySelector('#editEmployeeInfo').innerHTML = data; // Hiển thị dữ liệu nhân viên
    })
    .catch(error => console.error('Lỗi:', error));
});

document.getElementById('searchEmployeeButton').addEventListener('click', function() {
    const employeeId = document.getElementById('employeeIdManage').value;

    if (employeeId) {
        fetch('./PHP/search.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `employeeIdManage=${employeeId}&searchEmployee=true`
        })
        
        .then(response => response.json())
        .then(data => {
            const employeeInfoDiv = document.getElementById('employeeInfo');
            
            if (data.status === 'found') {
                const { EmployeeID, FullName, Email, Phone } = data.data;
                employeeInfoDiv.innerHTML = `
                    <p><strong>Mã Nhân Viên:</strong> ${EmployeeID}</p>
                    <p><strong>Họ Tên:</strong> ${FullName}</p>
                    <p><strong>Email:</strong> ${Email}</p>
                    <p><strong>Điện Thoại:</strong> ${Phone}</p>
                `;
            } else {
                employeeInfoDiv.innerHTML = "<p class='text-danger'>Không tìm thấy nhân viên với mã này.</p>";
            }
        })
        .catch(error => console.error('Error:', error));
    }
});



