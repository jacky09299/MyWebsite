<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>3D Orbiting Spheres</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
</head>
<body style="margin: 0; overflow: hidden;">
  <script>
    // 創建場景
    const scene = new THREE.Scene();

    // 創建相機
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 15;
    camera.position.y = 3;

    // 創建渲染器
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

    // 創建正方體
    const cubeGeometry = new THREE.BoxGeometry(2, 2, 2);
    const cubeMaterial = new THREE.MeshStandardMaterial({ color: 0x00ff00, metalness: 0.5, roughness: 0.5 });
    const cube = new THREE.Mesh(cubeGeometry, cubeMaterial);
    scene.add(cube);

    // 創建光源
    const light = new THREE.PointLight(0xffffff, 1, 100);
    light.position.set(10, 10, 10);
    scene.add(light);

    const ambientLight = new THREE.AmbientLight(0x404040);
    scene.add(ambientLight);

    // 公轉參數
    const orbitRadius = 4; // 公轉半徑
    const sphereCount = 8; // 球的數量
    const spheres = []; // 保存所有球體
    const colors = [0xff0000, 0x0000ff, 0xffff00, 0xff00ff, 0x00ffff, 0xffa500, 0x800080, 0x008000]; // 不同顏色

    // 創建多個球體
    for (let i = 0; i < sphereCount; i++) {
      const sphereGeometry = new THREE.SphereGeometry(0.3, 32, 32);
      const sphereMaterial = new THREE.MeshStandardMaterial({ color: colors[i % colors.length], metalness: 0.5, roughness: 0.5 });
      const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);

      // 初始位置
      const angle = (i / sphereCount) * Math.PI * 2; // 平均分布的角度
      sphere.position.set(Math.cos(angle) * orbitRadius, 0, Math.sin(angle) * orbitRadius);

      // 保存球的角度和物件
      spheres.push({ mesh: sphere, angle });
      scene.add(sphere);
    }

    // 動畫函數
    function animate() {
      requestAnimationFrame(animate);

      // 正方體自轉
      cube.rotation.y += 0.01;

      // 球體公轉
      spheres.forEach((sphereObj, index) => {
        sphereObj.angle += 0.01; // 更新角度 (可調整速度)
        const radiusOffset = index * 0.1; // 可選：每個球的半徑稍有不同
        sphereObj.mesh.position.x = Math.cos(sphereObj.angle) * (orbitRadius + radiusOffset);
        sphereObj.mesh.position.z = Math.sin(sphereObj.angle) * (orbitRadius + radiusOffset);
        sphereObj.mesh.position.y = Math.sin(sphereObj.angle * 2) * 1; // 添加上下波動
      });

      renderer.render(scene, camera);
    }

    animate();

    // 窗口大小調整
    window.addEventListener('resize', () => {
      renderer.setSize(window.innerWidth, window.innerHeight);
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
    });
  </script>
</body>
</html>
