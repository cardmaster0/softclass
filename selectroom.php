<?php
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$room = $_GET['room'] ?? '';

if ($date === '' || $time === '' || $room === '') {
  echo "入力が不足しています。";
  exit;
}

// 表示用に軽くエスケープ（XSS対策）
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

echo "選択された内容: " . h($date) . " " . h($time) . " / 教室 " . h($room);

// ここから予約処理（DB登録など）を書ける
?>
