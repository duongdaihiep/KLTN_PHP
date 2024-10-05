function generateCalendar(month, year) {
    const calendarBody = document.getElementById("calendar-body");
    const monthYear = document.getElementById("month-year");
    monthYear.textContent = `Tháng ${month + 1} năm ${year}`;

    // Get the first day of the month
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();
    const today = new Date().getDate();
    
    // Adjust first day for array index (0 = Sunday)
    const startDay = (firstDay === 0) ? 6 : firstDay - 1;

    // Generate empty slots for days before the first day of the month
    let rows = Math.ceil((startDay + lastDate) / 7);
    let calendarHTML = '';

    // Create rows
    for (let i = 0; i < rows; i++) {
        calendarHTML += '<tr>';
        for (let j = 0; j < 7; j++) {
            const dayNumber = i * 7 + j - startDay + 1;
            if (dayNumber > 0 && dayNumber <= lastDate) {
                let className = '';
                // Determine the class based on the date
                if (month === new Date().getMonth() && dayNumber < today) {
                    className = 'past-day'; // Days already passed
                } else if (month === new Date().getMonth() && dayNumber === today) {
                    className = 'current-day'; // Current day
                } else {
                    className = 'future-day'; // Future days
                }
                calendarHTML += `<td class="${className}">${dayNumber}</td>`;
            } else {
                calendarHTML += '<td></td>';
            }
        }
        calendarHTML += '</tr>';
    }

    calendarBody.innerHTML = calendarHTML;
}

// Get current month and year
const now = new Date();
generateCalendar(now.getMonth(), now.getFullYear());
