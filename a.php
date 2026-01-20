<?php
session_start();
if (!isset($_SESSION['id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: mainmenu.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>管理者画面</title></head>
<body>
<h1>管理者メニュー</h1>

<p>ここに全予約一覧とかを書く</p>

<a href="mainmenu.php">戻る</a>
</body>
</html>
