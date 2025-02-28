<?php
session_start();  // 啟動會話
include '../../common/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI 影像辨識 - 攝像頭與照片選擇</title>
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet"></script>
  <style>
    video, img {
      border: 2px solid #333;
      border-radius: 10px;
      width: 80%;
      max-width: 600px;
      margin-top: 10px;
    }
    button {
      margin: 10px;
      padding: 10px 20px;
      font-size: 16px;
    }
    #predictions {
      margin-top: 20px;
      list-style-type: none;
      padding: 0;
    }
    #predictions li {
      font-size: 18px;
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <h1>AI 影像辨識</h1>

  <!-- 按钮选择功能 -->
  <div>
    <button id="useFrontCamera">使用前置攝像頭</button>
    <button id="useBackCamera">使用後置攝像頭</button>
    <button id="uploadImage">選擇圖片</button>
  </div>

  <!-- 摄像头或图片显示 -->
  <video id="camera" autoplay playsinline></video>
  <img id="uploadedImage" src="" style="display: none;" alt="Uploaded Image">

  <!-- 分类结果 -->
  <h3>辨識結果:</h3>
  <ul id="predictions"></ul>

  <input type="file" id="imageInput" accept="image/*" style="display: none;">

  <script>
    const videoElement = document.getElementById('camera');
    const uploadedImage = document.getElementById('uploadedImage');
    const predictionsList = document.getElementById('predictions');
    const imageInput = document.getElementById('imageInput');

    let currentStream = null; // 当前摄像头流

    // 初始化摄像头
    async function initCamera(facingMode = 'user') {
      if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
      }

      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode },
          audio: false
        });

        currentStream = stream;
        videoElement.srcObject = stream;
        videoElement.style.display = 'block';
        uploadedImage.style.display = 'none';

        startRecognition(videoElement);
      } catch (error) {
        alert('無法啟用攝像頭: ' + error.message);
      }
    }

    // 加载 MobileNet 模型并识别
    async function startRecognition(sourceElement) {
      const model = await mobilenet.load();
      console.log('模型加載完成');

      // 定时进行辨识
      setInterval(async () => {
        const predictions = await model.classify(sourceElement);
        updatePredictions(predictions);
      }, 1000); // 每秒辨识一次
    }

    // 更新预测结果
    function updatePredictions(predictions) {
      predictionsList.innerHTML = '';
      predictions.forEach(prediction => {
        const listItem = document.createElement('li');
        listItem.textContent = `${prediction.className}: ${(prediction.probability * 100).toFixed(2)}%`;
        predictionsList.appendChild(listItem);
      });
    }

    // 上传图片并分析
    imageInput.addEventListener('change', async (event) => {
      const file = event.target.files[0];
      if (!file) return;

      const imgURL = URL.createObjectURL(file);
      uploadedImage.src = imgURL;
      uploadedImage.style.display = 'block';
      videoElement.style.display = 'none';

      const model = await mobilenet.load();
      uploadedImage.onload = async () => {
        const predictions = await model.classify(uploadedImage);
        updatePredictions(predictions);
      };
    });

    // 按钮事件监听
    document.getElementById('useFrontCamera').addEventListener('click', () => initCamera('user')); // 前置摄像头
    document.getElementById('useBackCamera').addEventListener('click', () => initCamera('environment')); // 后置摄像头
    document.getElementById('uploadImage').addEventListener('click', () => imageInput.click()); // 上传图片

    // 默认启动前置摄像头
    initCamera();
  </script>
</body>
</html>