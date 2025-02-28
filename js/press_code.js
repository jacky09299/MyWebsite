let clickIntervals = [NaN, NaN, NaN, NaN, NaN, NaN, NaN, NaN];
let pattern = [
    (value) => value < 0.5,  // < 0.5
    (value) => value < 0.5,  // < 0.5
    (value) => value > 1,    // > 1
    (value) => value > 1,    // > 1
    (value) => value < 0.5,  // < 0.5
    (value) => value < 0.5,  // < 0.5
    (value) => value < 0.5,   // < 0.5
    (value) => value > 1
];
let previousTime = NaN;

// 監聽點擊事件
function registerClick(event) {

    const currentTime = Date.now();
    // 計算與上次點擊的間隔
    let interval = (currentTime - previousTime) / 1000;
    previousTime = currentTime;

    // 移動陣列元素，將每個元素向前移動一格
    for (let i = 0; i < clickIntervals.length - 1; i++) {
        clickIntervals[i] = clickIntervals[i + 1];
    }

    // 把新的間隔加到數組的最後一格
    clickIntervals[clickIntervals.length - 1] = interval;

    // 每次點擊後，檢查是否符合模式
    if (checkPattern(clickIntervals)) {
        const userConfirmed = window.confirm("是否要打開首頁編輯器？"); // 顯示確認對話框
        if (userConfirmed) {
            window.location.href = "../info/home_editor.php"; // 跳轉到指定頁面
        }

        // 清空點擊記錄並重置為 7 個 NaN
        clickIntervals = [NaN, NaN, NaN, NaN, NaN, NaN, NaN,NaN];
        previousTime = NaN;
    }
}

// 檢查點擊間隔是否符合模式
function checkPattern(intervals) {
    // 檢查每一個間隔是否符合模式
    for (let i = 0; i < pattern.length; i++) {
        if (!pattern[i](intervals[i])) return false;
    }
    return true;
}


// 綁定點擊和觸控事件
document.body.addEventListener('pointerdown', registerClick);