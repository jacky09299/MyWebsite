<?php
session_start();
?>

<?php
function isMobile() {
    return preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $_SERVER['HTTP_USER_AGENT']);
}

if (isMobile()) {
    include 'common/navbar.php';
} else {
    include 'common/navbar.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>李紘宇的網站</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo filemtime('css/style.css'); ?>">
    <link rel="stylesheet" href="css/snowflake.css?v=<?php echo filemtime('css/snowflake.css'); ?>">
     <link rel="stylesheet" href="css/indent.css?v=<?php echo filemtime('css/indent.css'); ?>">
    <script src="js/snowflake.js?v=<?php echo filemtime('js/snowflake.js'); ?>"></script>
</head>
<body>
    <section id="intro">
    <h2>網站介紹</h2>
    <pre class="pre-wrap" id="intro-content"></pre>
  </section>

  <section id="resume">
    <h2>履歷</h2>
    <pre class="pre-wrap" id="resume-content"></pre>
  </section>

  <script src="js/txt_to_home.js?v=<?php echo filemtime('js/txt_to_home.js'); ?>"></script>

  <script src="js/press_code.js?v=<?php echo filemtime('js/press_code.js'); ?>"></script>
    <div class="snowflake-container"></div>
</body>
</html>
