function showSection(sectionId) {
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.add('d-none');
    });
    const section = document.getElementById(sectionId);
    if (section) { // Kiểm tra nếu section tồn tại
        section.classList.remove('d-none');
    } else {
        console.warn(`Không tìm thấy phần với ID: ${sectionId}`);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedSection = localStorage.getItem('currentSection');
    if (savedSection && document.getElementById(savedSection)) {
        showSection(savedSection);
    } else {
        showSection('shiftScheduling'); // Mặc định hiển thị phần "shiftScheduling" nếu không có giá trị hợp lệ trong localStorage
    }
});

function saveCurrentSection(sectionId) {
    localStorage.setItem('currentSection', sectionId);
}

// Chỉnh form để lưu section trước khi submit
document.querySelector("form").addEventListener("submit", function() {
    saveCurrentSection("attendanceApproval");
});


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


