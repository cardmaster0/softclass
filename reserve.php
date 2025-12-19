<?php
$date  = $_GET['date'] ?? '';
$room  = $_GET['room'] ?? '';
$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

if ($date==='' || $room==='' || $start==='' || $end==='') {
  echo "入力が不足しています。"; exit;
}

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$start_ts = strtotime("$date $start");
$end_ts   = strtotime("$date $end");

if ($start_ts === false || $end_ts === false) { echo "時刻形式が不正です。"; exit; }
if ($end_ts <= $start_ts) { echo "終了時刻は開始時刻より後にしてください。"; exit; }

$diff = $end_ts - $start_ts;
if ($diff % (15 * 60) !== 0) { echo "15分単位で選択してください。"; exit; }

// ここから DB：同じ date+room の予約と重複しないかチェック→INSERT
echo "予約内容：".h($date)." / ".h($room)." / ".h($start)."〜".h($end);
?>
