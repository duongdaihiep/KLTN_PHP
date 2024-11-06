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


 // Lắng nghe sự thay đổi giữa các radio button để hiển thị đúng input
 document.querySelectorAll('input[name="shiftOption"]').forEach(input => {
    input.addEventListener('change', function() {
        if (this.value === 'bulk') {
            document.getElementById('shiftDateContainer').style.display = 'none';
            document.getElementById('shiftMonthContainer').style.display = 'block';
        } else {
            document.getElementById('shiftDateContainer').style.display = 'block';
            document.getElementById('shiftMonthContainer').style.display = 'none';
        }
    });
});


$(document).ready(function() {
    // Mặc định chọn Xếp Ca Theo Ngày
    $('#singleDayShift').addClass('active');
    $('#shiftDateWrapper').show();
    $('#shiftMonthWrapper').hide();

    // Khi người dùng nhấn vào nút "Xếp Ca Theo Ngày"
    $('#singleDayShift').click(function() {
        $('#singleDayShift').addClass('active');
        $('#bulkShift').removeClass('active');
        $('#shiftDateWrapper').show();
        $('#shiftMonthWrapper').hide();
    });

    // Khi người dùng nhấn vào nút "Xếp Ca Đồng Loạt Trong Tháng"
    $('#bulkShift').click(function() {
        $('#bulkShift').addClass('active');
        $('#singleDayShift').removeClass('active');
        $('#shiftDateWrapper').hide();
        $('#shiftMonthWrapper').show();
    });
});

// submit xếp ca 
$(document).ready(function() {
    // Mặc định chọn "Xếp Ca Theo Ngày"
    $('#singleDayShift').addClass('active');
    $('#shiftDateWrapper').show();
    $('#shiftMonthWrapper').hide();

    // Khi người dùng nhấn vào nút "Xếp Ca Theo Ngày"
    $('#singleDayShift').click(function() {
        $('#singleDayShift').addClass('active');
        $('#bulkShift').removeClass('active');
        $('#shiftDateWrapper').show();
        $('#shiftMonthWrapper').hide();
    });

    // Khi người dùng nhấn vào nút "Xếp Ca Đồng Loạt Trong Tháng"
    $('#bulkShift').click(function() {
        $('#bulkShift').addClass('active');
        $('#singleDayShift').removeClass('active');
        $('#shiftDateWrapper').hide();
        $('#shiftMonthWrapper').show();
    });

    // Khi form được submit
    $('form').submit(function(event) {
        event.preventDefault(); // Ngăn chặn form submit mặc định

        // Lấy dữ liệu từ form
        var employeeId = $('#employeeIdShift').val();
        var shiftTime = $('#shiftTime').val();
        var shiftDate = $('#shiftDate').val(); // Ngày
        var shiftMonth = $('#shiftMonth').val(); // Tháng

        // Kiểm tra kiểu xếp ca
        var shiftOption = $('button.active').data('shift');

        // Chuẩn bị dữ liệu gửi đến server
        var data = {
            employeeId: employeeId,
            shiftTime: shiftTime,
            shiftOption: shiftOption,
            shiftDate: shiftDate,
            shiftMonth: shiftMonth
        };

        // Xử lý xếp ca theo ngày
        if (shiftOption === 'singleDay' && shiftDate) {
            data.workDate = shiftDate; // Dữ liệu ngày
        }

        // Xử lý xếp ca theo tháng
        if (shiftOption === 'bulk' && shiftMonth) {
            data.workMonth = shiftMonth; // Dữ liệu tháng
        }

        // Gửi yêu cầu AJAX đến server
        $.ajax({
            url: 'your_backend_php_file.php', // Đổi thành đường dẫn đúng tới file PHP của bạn
            method: 'POST',
            data: data,
            success: function(response) {
                alert('Xếp ca thành công');
            },
            error: function(xhr, status, error) {
                alert("Lỗi khi xếp ca: " + error);
            }
        });
    });
});

