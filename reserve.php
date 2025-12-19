<?php
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$room = $_GET['room'] ?? '';

if ($date === '' || $time === '' || $room === '') {
  echo "入力が不足しています。";
  exit;
}

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

echo "予約内容：". h($date) ." ". h($time) ." / 教室 ". h($room);

// ここから先で DB へ重複チェック→INSERT を実装
?>
