<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>日付選択</title>
</head>
<body>
  <form action="selectroom.php" method="get">
    <label>日付</label>
    <input type="date" name="date" required /><!--カレンダーを作ってるさらに入力まで-->
    <button type="submit">教室選択へ</button>
  </form>
</body>
</html>
