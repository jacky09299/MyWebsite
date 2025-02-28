let selectedDate = null;
let reservedDates = {};  // 記錄已預約的日期
let page = window.location.pathname.includes('page1') ? 'page1' : 'page2';  // 確定是 page1 還是 page2

document.addEventListener("DOMContentLoaded", function () {
    const calendarElement = document.getElementById('calendar');
    const confirmButton = document.getElementById("confirmButton");
    const selectedDateInput = document.getElementById('selectedDate');
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    // 填充年份和月份選單
    function populateYearMonth() {
        for (let i = currentYear - 5; i <= currentYear + 5; i++) {
            let option = document.createElement('option');
            option.value = i;
            option.text = i;
            if (i === currentYear) option.selected = true;
            yearSelect.appendChild(option);
        }

        for (let i = 0; i < 12; i++) {
            let option = document.createElement('option');
            option.value = i;
            option.text = (i + 1) + '月';
            if (i === currentMonth) option.selected = true;
            monthSelect.appendChild(option);
        }
    }

    // 生成日曆並根據預約數量顯示顏色
    function generateCalendar(year, month) {
        calendarElement.innerHTML = ''; 
        const date = new Date(year, month);
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

        let calendarHTML = '<table><tr>';
        const weekdays = ["日", "一", "二", "三", "四", "五", "六"];
        weekdays.forEach(day => {
            calendarHTML += `<th>${day}</th>`;
        });
        calendarHTML += '</tr><tr>';

        for (let i = 0; i < firstDay.getDay(); i++) {
            calendarHTML += '<td></td>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateString = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            let isReserved = reservedDates[dateString] && reservedDates[dateString].count >= 3 ? 'red' : '';
            let isClickable = isReserved ? 'style="pointer-events: none;"' : '';
            
            calendarHTML += `<td class="${isReserved}" ${isClickable} onclick="selectDate('${dateString}')">${day}</td>`;
            if ((day + firstDay.getDay()) % 7 === 0) calendarHTML += '</tr><tr>';
        }
        calendarHTML += '</tr></table>';
        calendarElement.innerHTML = calendarHTML;
    }

    // 更新日曆
    function updateCalendar() {
        fetchReservations().then(() => {
            generateCalendar(parseInt(yearSelect.value), parseInt(monthSelect.value));
        });
    }

    // 獲取已預約的日期並更新 reservedDates
    async function fetchReservations() {
        // 使用假設的 API 呼叫獲取預約數據
        const response = await fetch('fetchReservations.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ page: page })  // 傳入當前頁面
        });
        reservedDates = await response.json();
    }

    yearSelect.addEventListener('change', updateCalendar);
    monthSelect.addEventListener('change', updateCalendar);

    window.selectDate = function (date) {
        selectedDate = date;
        selectedDateInput.value = date;
        confirmButton.style.display = "block";
    };

    window.confirmReservation = function (apiUrl, page) {
        if (selectedDate) {
            fetch(apiUrl, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    action: "reserve",
                    date: selectedDate,
                    page: page
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert("預約成功！");
                    location.reload();
                } else {
                    alert("此日期預約已達上限。");
                }
            })
            .catch(error => {
                console.error("發生錯誤:", error);
            });
        } else {
            alert("請先選擇日期！");
        }
    };

    populateYearMonth();
    updateCalendar();
});
