<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$date = $_GET['date'] ?? '';
if ($date === '') { echo "日付が未選択です。"; exit; }
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>教室選択</title></head>
<body>
  <p>選択日：<?= h($date) ?></p>

  <form action="timeselect.php" method="get">
    <input type="hidden" name="date" value="<?= h($date) ?>">

    <label>教室</label>
    <select name="room" required>
      <option value="" selected disabled>教室を選択</option>
      <option value="研究室">研究室</option>
      <option value="S棟">AS棟</option>
      <option value="G30">G30</option>
      <option value="S21">S21</option>
    </select>

    <button type="submit">時間選択へ</button>
  </form>
  <a href="dayselect.php">日付選択に戻る</a>
</body>
</html>
