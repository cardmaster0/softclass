<?php
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';

if ($date === '' || $time === '') {
  echo "日時が未選択です。";
  exit;
}

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>教室選択</title>
  </head>

  <body>
    <p>選択された日時：<?= h($date) ?> <?= h($time) ?></p>

    <form action="reserve.php" method="get">
      <!-- 日時を引き継ぐ（ここが重要） -->
      <input type="hidden" name="date" value="<?= h($date) ?>" />
      <input type="hidden" name="time" value="<?= h($time) ?>" />

      <div>
        <label>教室</label>
        <select name="room" required>
          <option value="" selected disabled>教室を選択</option>
          <option value="A101">A101</option>
          <option value="A102">A102</option>
          <option value="B201">B201</option>
          <option value="B202">B202</option>
        </select>
      </div>

      <button type="submit">予約へ進む</button>
    </form>
  </body>
</html>
