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

