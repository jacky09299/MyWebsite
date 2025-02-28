<?php
session_start();
include '../../common/navbar_mobile.php';
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=0">
    <title> Artistic Image Tiles </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.9.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    
    <style>
        body { font-family: Arial, sans-serif; }
        #editor { display: grid; gap: 2px; border: 2px solid #333; margin: 20px auto; width: fit-content; }
        .cell { border: 1px solid #aaa; display: flex; align-items: center; justify-content: center; position: relative; font-size: 16px; cursor: pointer; overflow: hidden; }
        .cell.selected { border: 2px solid #007bff; }
        .cell input { border: none; background: transparent; text-align: center; width: 100%; height: 100%; padding: 0; font-size: inherit; color: inherit; }
        .background-image { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; z-index: -1; }
        #cropModal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background-color: white; padding: 20px; border: 2px solid #000000; }
        * {
    box-sizing: border-box;
}

.container {
    max-width: 100%;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;  /* 允許換行 */
    gap: 15px;
}

.input-group {
    display: flex;
    flex-direction: column;
    width: calc(15% - 15px); /* 設定每個區塊的寬度為容器的 1/3 */
}

.input-group label {
    white-space: nowrap;
    font-weight: bold;
    margin-bottom: 5px;
}

.input-group input,
.input-group select {
    padding: 8px;
    font-size: 14px;
    margin-bottom: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.input-group input[type="number"] {
    width: 130px;
}

.input-group input[type="color"]{
    width: auto;
    margin-bottom: 5px;
}
.input-group input[type="file"]{
    width: 80%;
    margin-bottom: 5px;
}
.input-group input[type="range"]{
    width: 50%;
    margin-bottom: 5px;
}

.input-group button {
    padding: 12px 8px;
    width: 70%;
    background-color: #138120;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 3px;  /* 上方間距 */
    margin-bottom: 3px;  /* 下方間距 */
    white-space: nowrap;
}

.input-group button:hover {
    background-color: #137820;
}

.button-group {
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: calc(15% - 15px);
    gap: 15px;
}

.button-group button {
    padding: 0px 3px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    white-space: nowrap;
    height: auto;
}

.button-group button:hover {
    background-color: #218838;
}

button:focus {
    outline: none;
}

.color-picker {
    display: inline-block;
    width: 30px;  /* 圓形的寬度 */
    height: 30px; /* 圓形的高度 */
    border-radius: 50%; /* 設定圓形 */
    border: 2px solid #ccc; /* 加一點邊框讓圓形更加明顯 */
    background-color: #000000; /* 默認顏色 */
    overflow: hidden; /* 確保顏色選擇器不會溢出 */
    cursor: pointer;
}

.color-picker input[type="color"] {
    width: 100%;
    height: 100%;
    opacity: 0;  /* 隱藏顏色選擇器的實際方框 */
    cursor: pointer;
}

.color-picker input[type="color"]:focus {
    outline: none;
}

.flex-group {
    display: flex; /* 使用 Flexbox 排列內部元素 */
    align-items: center; /* 垂直對齊元素到中心 */
    gap: 10px; /* 控制 label 和 color picker 之間的間距 */
}

.gray-button {
    background-color: #EFEFEF !important; /* 灰色底色 */
    color: black !important; /* 黑色字體 */
    font-size: 14px !important; /* 12px字體 */
    width: 80px;
    padding: 5px 5px !important; /* 適中的內邊距，讓按鈕大小合適 */
    border: 1px solid black !important; /* 無邊框 */
    border-radius: 0px !important; /* 圓角邊框 */
    cursor: pointer !important; /* 鼠標懸停時顯示為指針 */
    text-align: center !important; /* 文字置中 */
    margin-top: 3px !important;  /* 上方間距 */
    margin-bottom: 3px !important;  /* 下方間距 */
    white-space: nowrap !important;
}

.gray-button:hover {
    background-color: #DCDCDC !important; /* 滑鼠懸停時變成較深的灰色 */
}

.gray-button:focus {
    outline: none !important; /* 點擊後移除外圍焦點框 */
}

@media (orientation: landscape) {
    .container {
    max-width: 100%;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;  /* 允許換行 */
    gap: 15px;
}
    .input-group {
        display: flex;
        width: calc(25% - 15px); /* 當視窗寬度較小時，寬度為 1/2 */
    }
}


@media (max-width: 768px) {
    .container {
    max-width: 100%;
    flex-direction: column;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;  /* 允許換行 */
    gap: 15px;
}
    .input-group {
        display: flex;
        width: calc(25% - 15px); /* 當視窗寬度較小時，寬度為 1/2 */
    }
}

@media (max-width: 480px) {
    .container {
    max-width: 100%;
    flex-direction: column;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;  /* 允許換行 */
    gap: 15px;
}
    .input-group {
        display: flex;
        width: 50%;  /* 更小的視窗時，每個輸入區域佔滿整行 */
    }
}

    </style>
</head>
<body>

<div class="container">
    <div class="input-group">
        <label for="width">文件大小 (x * y):</label>
        <input type="number" id="width" placeholder="寬" min="1" value="1000">
        <input type="number" id="height" placeholder="高" min="1" value="1000">
        <button onclick="setEditorSize()">設定大小</button>
    </div>

    <div class="input-group">
        <label for="rows">分割區域 (a * b):</label>
        <input type="number" id="rows" placeholder="行" min="1" value="4">
        <input type="number" id="cols" placeholder="列" min="1" value="3">
        <button onclick="splitEditor()">分割區域</button>
    </div>

    <div class="input-group">
        <div>
        <label for="fontSize">字體大小:</label>
        <select id="fontSize" onchange="setFontSize()" value="16px">
            <option value="16px">16px</option>
            <option value="18px">18px</option>
            <option value="24px">24px</option>
        </select>
        </div>
        <div class="flex-group">
            <label for="fontColor">字體顏色:</label>
            <div class="color-picker">
            <input type="color" id="fontColor" onchange="setFontColor()" value="#000000">
            </div>
        </div>
        
    </div>


    <div class="input-group">
        <label for="imageUpload">上傳背景圖片:</label>
        <input type="file" id="imageUpload" accept="image/*" onchange="setBackground()">
        <div class="flex-group">
            <label for="fontColor">或單色背景:</label>
            <div class="color-picker">
                <input type="color" id="colorPicker" onchange="setBackground()">
            </div>
        </div>
        <div class="flex-group">
            <label for="opacity">透明度:</label>
            <input type="range" id="opacity" min="0" max="1" step="0.1" value="1" onchange="setOpacity()">
        </div>
        
    </div>

    <div class="input-group">
        <button onclick="recropImage()">重新裁剪圖片</button>
        <button id="swapButton" onclick="enterSwapMode()">進入互換模式</button>
    </div>

    <div class="input-group">
        <label for="fileName">儲存檔名：</label>
        <input type="text" id="fileName" placeholder="檔案命名" />
        <button onclick="saveEditor()">儲存</button>
        <input type="file" id="loadFile" accept=".ait" onchange="loadEditor()" style="display: none;">
        <button class="gray-button" onclick="document.getElementById('loadFile').click()">開啟檔案</button>
        
        <button onclick="exportPDF()">匯出為PDF</button>
    </div>
</div>


<div id="editor"></div>

<!-- 裁剪彈出視窗 -->
<div id="cropModal">
    <img id="cropImage" style="max-width: 100%;">
    <button onclick="applyCrop()">確定裁剪</button>
    <button onclick="closeCropModal()">取消</button>
</div>


<script>
    let editor = document.getElementById('editor');
    let selectedCell = null;
    let cropper;
    let cellData = []; // 儲存每個格子的圖片、文字和樣式
    let previousCells = [];
    let oldRows = 0;
    let oldCols = 0;
    
    function setEditorSize() {
        const width = document.getElementById('width').value;
        const height = document.getElementById('height').value;
        editor.style.width = width + 'px';
        editor.style.height = height + 'px';
    }

    function splitEditor() {
    const rows = parseInt(document.getElementById('rows').value);
    const cols = parseInt(document.getElementById('cols').value);

    editor.style.gridTemplateRows = `repeat(${rows}, 1fr)`;
    editor.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;

    previousCells = Array.from(editor.querySelectorAll('.cell'));

    // 保存當前格子數據（僅在重新生成格子時有用）
    const cellData = previousCells.map((cell, index) => {
        const input = cell.querySelector('textarea');
        const img = cell.querySelector('.background-image');
        const originalRow = Math.floor(index / oldCols);
        const originalCol = index % oldCols;

        return {
            row: originalRow,
            col: originalCol,
            text: input.value,
            textColor: input.style.color,
            textFontSize: input.style.fontSize,
            fontSize: cell.style.fontSize,
            color: cell.style.color,
            imageSrc: img ? img.src : null,
            originalImageSrc: img ? img.dataset.originalSrc : null,
            opacity: img ? img.style.opacity : null
        };
    });

    // 清空編輯區
    editor.innerHTML = '';

    // 遍歷生成新的格子
    for (let i = 0; i < rows * cols; i++) {
        const row = Math.floor(i / cols);
        const col = i % cols;

        const cell = document.createElement('div');
        cell.className = 'cell';
        cell.draggable = true; // 設置為可拖曳
        cell.addEventListener('dragstart', handleDragStart);
        cell.addEventListener('dragover', handleDragOver);
        cell.addEventListener('drop', handleDrop);
        cell.addEventListener('click', () => selectCell(cell));

        // 創建輸入框
        const input = document.createElement('textarea');
        input.type = 'text';
        input.value = ''; // 默認輸入框為空
        input.style.fontSize = '16px'; // 默認字體大小
        input.style.color = '#000000'; // 默認字體顏色
        input.style.width = '100%'; // 讓 textarea 自適應 cell 的寬度
        input.style.height = '100%'; // 讓 textarea 自適應 cell 的高度
        input.style.resize = 'none'; // 禁用用戶調整大小
        input.style.border = 'none'; // 移除邊框
        input.style.outline = 'none'; // 去掉聚焦時的外框
        input.style.background = 'transparent'; // 背景透明，與父容器的背景一致
        input.style.padding = '0'; // 移除內邊距
        input.style.margin = '0'; // 移除外邊距
        input.style.overflow = 'hidden'; // 確保多行時不顯示滾動條
        cell.appendChild(input);

        // 默認背景圖片
        const img = document.createElement('img');
        img.className = 'background-image';
        img.src = generateColorImage("#ffffff"); // 替換為白色背景圖片的路徑
        img.dataset.originalSrc = img.src;
        img.style.opacity = '1'; // 默認透明度
        cell.appendChild(img);

        // 設置格子的默認樣式
        cell.style.fontSize = '16px';
        cell.style.color = '#000000';

        // 如果有保留的數據，填充到新格子
        const data = cellData.find(item => item.row === row && item.col === col);
        if (data) {
            input.value = data.text;
            cell.style.fontSize = data.fontSize || '16px';
            cell.style.color = data.color || '#000000';
            input.style.fontSize = data.textFontSize || '16px';
            input.style.color = data.textColor || '#000000';

            if (data.imageSrc) {
                img.src = data.imageSrc;
                img.dataset.originalSrc = data.originalImageSrc;
                img.style.opacity = data.opacity || '1';
            }
        }

        editor.appendChild(cell);
    }

    // 更新全局變量
    oldRows = rows;
    oldCols = cols;
}


    let swapMode = false; // 是否啟用互換模式
let firstCell = null; // 第一次選中的格子
let secondCell = null; // 第二次選中的格子

function enterSwapMode() {
    if (swapMode === false){
        swapMode = true; // 啟用互換模式
        document.getElementById("swapButton").textContent = "退出互換模式";
    }else{
        swapMode = false; // 禁用互換模式
        document.getElementById("swapButton").textContent = "進入互換模式"; 
    }
}

// 用來處理格子點擊的邏輯
function selectCellForSwap(cell) {
    if (!swapMode) return; // 如果不在互換模式下，則不執行

    if (!firstCell) {
        // 記錄第一次點擊的格子
        firstCell = cell;
        firstCell.classList.add('selected');
       
    } else if (!secondCell) {
        // 記錄第二次點擊的格子
        secondCell = cell;
        secondCell.classList.add('selected');
        swapCells(firstCell, secondCell); // 交換兩個格子的內容
        firstCell.classList.remove('selected');
        secondCell.classList.remove('selected');
        firstCell = null;
        secondCell = null;
    }
}



// 將原有的 selectCell 函數更新為 selectCellForSwap
function selectCell(cell) {
    if (swapMode) {
        selectCellForSwap(cell);
    } else {
        if (selectedCell) selectedCell.classList.remove('selected');
        selectedCell = cell;
        selectedCell.classList.add('selected');
    }
}

    function setFontSize() {
        const fontSize = document.getElementById('fontSize').value;
        if (selectedCell) {
            selectedCell.style.fontSize = fontSize;
            selectedCell.querySelector('textarea').style.fontSize = fontSize;
        }
    }

    function setFontColor() {
        const fontColor = document.getElementById('fontColor').value;
        if (selectedCell) {
            selectedCell.style.color = fontColor;
            selectedCell.querySelector('textarea').style.color = fontColor;
        }
    }
    
    function generateColorImage(color) {
    const canvas = document.createElement('canvas');
    canvas.width = 1000;
    canvas.height = 1000;

    const ctx = canvas.getContext('2d');
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, 1000, 1000);
    return canvas.toDataURL(); // 返回顏色圖片的 Data URL
}

    function setBackground() {
        let file = document.getElementById('imageUpload').files[0];
        let colorPicker = document.getElementById('colorPicker');
        const selectedColor = colorPicker.value;
        if (file && selectedCell) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageSrc = e.target.result;
                let img = selectedCell.querySelector('.background-image');
                if (!img) {
                    img = document.createElement('img');
                    img.className = 'background-image';
                    selectedCell.appendChild(img);
                }
                img.src = imageSrc;
                img.dataset.originalSrc = imageSrc;
            };
            reader.readAsDataURL(file);
        }else if (selectedColor && selectedCell){
            let img = selectedCell.querySelector('.background-image');
            const colorImage = generateColorImage(selectedColor);
            if (!img) {
                    img = document.createElement('img');
                    img.className = 'background-image';
                    selectedCell.appendChild(img);
                }
                img.src = colorImage;
                img.dataset.originalSrc = colorImage;
        }
        document.getElementById('imageUpload').value = "";
        document.getElementById('colorPicker').value = "#000000";
    }

    function openCropModal(imageSrc) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = imageSrc;
        document.getElementById('cropModal').style.display = 'block';

        if (cropper) cropper.destroy();

        const aspectRatio = selectedCell.offsetWidth / selectedCell.offsetHeight;
        cropper = new Cropper(cropImage, { aspectRatio, viewMode: 1 });
    }

    function applyCrop() {
        if (cropper) {
            const croppedCanvas = cropper.getCroppedCanvas();
            const croppedImage = croppedCanvas.toDataURL();

            let img = selectedCell.querySelector('.background-image');
            if (!img) {
                img = document.createElement('img');
                img.className = 'background-image';
                selectedCell.appendChild(img);
            }
            img.src = croppedImage;
            closeCropModal();
        }
    }

    function closeCropModal() {
        document.getElementById('cropModal').style.display = 'none';
        if (cropper) cropper.destroy();
    }

    function recropImage() {
        const img = selectedCell.querySelector('.background-image');
        if (img) {
            openCropModal(img.dataset.originalSrc);
        }
    }

    function setOpacity() {
        const opacity = document.getElementById('opacity').value;
        const img = selectedCell ? selectedCell.querySelector('.background-image') : null;
        if (img) {
            img.style.opacity = opacity;
        }
    }

    // 拖曳開始
    function handleDragStart(event) {
        draggedCell = event.currentTarget;
    }

    // 拖曳過程中允許放置
    function handleDragOver(event) {
        event.preventDefault();
    }

    // 拖曳放置後交換內容
    function handleDrop(event) {
        event.preventDefault();
        swapCells(draggedCell,event.currentTarget);
    }

    function swapCells(cell1,cell2){
        if (cell1 !== cell2) {
            // 交換兩個區域的內容
            const draggedInput = cell1.querySelector('textarea');
            const targetInput = cell2.querySelector('textarea');

            const tempContent = draggedInput.value;
            draggedInput.value = targetInput.value;
            targetInput.value = tempContent;
            
            const tempFontSize = draggedInput.style.fontSize;
            draggedInput.style.fontSize = targetInput.style.fontSize;
            targetInput.style.fontSize = tempFontSize;
            
            const tempColor = draggedInput.style.color;
            draggedInput.style.color = targetInput.style.color;
            targetInput.style.color = tempColor;

            // 交換背景圖片和透明度
            const draggedImg = cell1.querySelector('img');
            const targetImg = cell2.querySelector('img');

            const tempImg = draggedImg.src;
            draggedImg.src = targetImg.src;
            targetImg.src = tempImg;
            
            const tempOriginalImg = draggedImg.dataset.originalSrc;
            draggedImg.dataset.originalSrc = targetImg.dataset.originalSrc;
            targetImg.dataset.originalSrc = tempOriginalImg;
            
            const tempOpacity = draggedImg.style.opacity;
            draggedImg.style.opacity = targetImg.style.opacity;
            targetImg.style.opacity = tempOpacity;
            
            cell1.style.fontSize = cell2.style.fontSize;
            cell2.style.fontSize = tempFontSize;
            
            cell1.style.color = cell2.style.color;
            cell2.style.color = tempColor;
            
            const draggedIndex = Array.from(editor.querySelectorAll('.cell')).indexOf(cell1);
            const targetIndex = Array.from(editor.querySelectorAll('.cell')).indexOf(cell2);
            const tempCellData = previousCells[draggedIndex];
            previousCells[draggedIndex] = previousCells[targetIndex];
            previousCells[targetIndex] = tempCellData;

            
        }
    }

    
    function saveEditor() {
    // 獲取用戶輸入的檔案名稱，並加上 .ait 副檔名
    let fileName = document.getElementById('fileName').value.trim();
    if (!fileName) {
        fileName = 'editorData'; // 如果沒輸入檔名，則使用預設檔名
    }
    fileName += '.ait'; // 強制使用 .ait 副檔名
    const editorData = {
        width: editor.style.width,
        height: editor.style.height,
        rows: document.getElementById('rows').value,
        cols: document.getElementById('cols').value,
        previousCells,
        cellData,
        cells: Array.from(editor.querySelectorAll('.cell')).map(cell => {
            const input = cell.querySelector('textarea');
            const img = cell.querySelector('.background-image');
            return {
                text: input ? input.value : '', // 儲存輸入的文字
                textFontSize: input ? input.style.fontSize : '', // 輸入框的字體大小
                textColor: input ? input.style.color : '', // 輸入框的字體顏色
                fontSize: cell.style.fontSize || '', // 格子的字體大小
                color: cell.style.color || '', // 格子的字體顏色
                backgroundImage: img ? img.src : null, // 背景圖片的路徑
                originalImageSrc: img ? img.dataset.originalSrc : null, // 圖片的原始路徑
                opacity: img ? img.style.opacity : '', // 背景圖片透明度
            };
        }),
    };

    const json = JSON.stringify(editorData);
    const blob = new Blob([json], { type: 'application/json' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = fileName;
    a.click();

    URL.revokeObjectURL(url);
}
    function loadEditor() {
    const file = document.getElementById('loadFile').files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const editorData = JSON.parse(e.target.result);
        restoreEditor(editorData);
    };
    reader.readAsText(file);
}

function restoreEditor(editorData) {
    // 恢復基本屬性
    editor.style.width = editorData.width;
    editor.style.height = editorData.height;

    document.getElementById('rows').value = editorData.rows;
    document.getElementById('cols').value = editorData.cols;

    // 恢復格子
    splitEditor();

    const cells = editor.querySelectorAll('.cell');
    editorData.cells.forEach((data, index) => {
        const cell = cells[index];
        const input = cell.querySelector('textarea');
        const img = cell.querySelector('.background-image');

        if (input) {
            input.value = data.text || '';
            input.style.fontSize = data.textFontSize || '16px';
            input.style.color = data.textColor || '#000000';
        }

        if (img) {
            img.src = data.backgroundImage || generateColorImage("#ffffff");
            img.dataset.originalSrc = data.originalImageSrc || img.src;
            img.style.opacity = data.opacity || '1';
        }

        cell.style.fontSize = data.fontSize || '16px';
        cell.style.color = data.color || '#000000';
    });

    // 恢復 previousCells 和 cellData
    previousCells = editorData.previousCells || [];
    cellData = editorData.cellData || [];
}


    function exportPDF() {
        disableCellBorder();
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // 你可以自定義大小，這裡假設為A4紙
    const width = 210; // A4寬度
    const height = 297; // A4高度
    
    // 將editor轉換為圖片，並添加到PDF中
    html2canvas(editor).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg');
        doc.addImage(imgData, 'JPEG', 10, 10, width - 20, (canvas.height * (width - 20)) / canvas.width);
        doc.save('editor.pdf');
    });
    enableCellBorder();
}

// 禁用 .cell.selected 邊框
function disableCellBorder() {
    // 創建一個style標籤
    const style = document.createElement('style');
    style.setAttribute('id', 'disable-cell-border-style');  // 設置一個ID，方便日後移除
    style.innerHTML = `
        .cell.selected {
            border: none !important;  // 禁用邊框
        }
    `;
    // 將style標籤添加到頁面的head部分
    document.head.appendChild(style);
}

// 恢復 .cell.selected 邊框
function enableCellBorder() {
    // 查找並移除之前添加的style標籤
    const style = document.getElementById('disable-cell-border-style');
    if (style) {
        style.remove();
    }
}





    // 初始化
    setEditorSize();
    splitEditor();
</script>

</body>
</html>
