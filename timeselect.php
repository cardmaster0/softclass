<?php
session_start();
$date = $_GET['date'] ?? '';
$room = $_GET['room'] ?? '';
if ($date === '' || $room === '') { echo "日付または教室が未選択です。"; exit; }
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// 15分刻みの時刻を生成（ここでは 00:00〜23:45）
$times = [];
for ($h = 0; $h <= 23; $h++) {
  for ($m = 0; $m <= 45; $m += 15) {
    $times[] = sprintf("%02d:%02d", $h, $m);
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>時間選択</title></head>
<body>
  <p>選択内容：<?= h($date) ?> / <?= h($room) ?></p>

  <form action="reserve.php" method="get">
    <input type="hidden" name="date" value="<?= h($date) ?>">
    <input type="hidden" name="room" value="<?= h($room) ?>">

    <div>
      <label>開始</label>
      <select name="start" required>
        <option value="" selected disabled>開始時刻</option>
        <?php foreach ($times as $t): ?>
          <option value="<?= h($t) ?>"><?= h($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label>終了</label>
      <select name="end" required>
        <option value="" selected disabled>終了時刻</option>
        <?php foreach ($times as $t): ?>
          <option value="<?= h($t) ?>"><?= h($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit">予約へ進む</button>
  </form>

  <p>注意：終了は開始より後の時刻を選んでください（15分単位）。</p>
</body>
</html>

