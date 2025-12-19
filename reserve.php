<?php
// reserve.php

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// GETで受け取る（フォームが method="get" 前提）
$date  = $_GET['date']  ?? '';
$room  = $_GET['room']  ?? '';
$start = $_GET['start'] ?? '';
$end   = $_GET['end']   ?? '';

// 不足項目の特定
$missing = [];
if ($date  === '') $missing[] = 'date';
if ($room  === '') $missing[] = 'room';
if ($start === '') $missing[] = 'start';
if ($end   === '') $missing[] = 'end';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>予約確認</title>
</head>
<body>

<?php if (!empty($missing)): ?>
  <h2>入力が不足しています</h2>
  <p>不足している項目：<b><?= h(implode(', ', $missing)) ?></b></p>

  <p><a href="dayselect.php">日付選択へ戻る</a></p>

  <?php if ($date !== ''): ?>
    <p>
      <a href="selectroom.php?date=<?= urlencode($date) ?>">
        教室選択へ戻る
      </a>
    </p>
  <?php endif; ?>

  <?php if ($date !== '' && $room !== ''): ?>
    <p>
      <a href="timeselect.php?date=<?= urlencode($date) ?>&room=<?= urlencode($room) ?>">
        時間選択へ戻る
      </a>
    </p>
  <?php endif; ?>

<?php
  exit;
endif;

// 時刻の妥当性チェック
$start_ts = strtotime("$date $start");
$end_ts   = strtotime("$date $end");

if ($start_ts === false || $end_ts === false) {
  echo "<h2>日付または時刻形式が不正です</h2>";
  echo '<p><a href="timeselect.php?date='.urlencode($date).'&room='.urlencode($room).'">時間選択へ戻る</a></p>';
  exit;
}

if ($end_ts <= $start_ts) {
  echo "<h2>終了時刻は開始時刻より後にしてください</h2>";
  echo '<p><a href="timeselect.php?date='.urlencode($date).'&room='.urlencode($room).'">時間選択へ戻る</a></p>';
  exit;
}

$diff = $end_ts - $start_ts;
if ($diff % (15 * 60) !== 0) {
  echo "<h2>15分単位で選択してください</h2>";
  echo '<p><a href="timeselect.php?date='.urlencode($date).'&room='.urlencode($room).'">時間選択へ戻る</a></p>';
  exit;
}
?>

<h2>予約内容</h2>
<ul>
  <li>日付：<?= h($date) ?></li>
  <li>教室：<?= h($room) ?></li>
  <li>時間：<?= h($start) ?> ～ <?= h($end) ?></li>
</ul>

<!-- ここからDBの重複チェック → INSERT を実装 -->

<p>
  <a href="timeselect.php?date=<?= urlencode($date) ?>&room=<?= urlencode($room) ?>">
    時間選択へ戻る
  </a>
</p>

<p>
  <a href="selectroom.php?date=<?= urlencode($date) ?>">
    教室選択へ戻る
  </a>
</p>

</body>
</html>
