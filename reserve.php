<?php
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// まずはGET/POSTどちらでも受け取れるようにする（切り分け用）
$date  = $_REQUEST['date']  ?? '';
$room  = $_REQUEST['room']  ?? '';
$start = $_REQUEST['start'] ?? '';
$end   = $_REQUEST['end']   ?? '';

// 不足項目を列挙
$missing = [];
if ($date  === '') $missing[] = 'date';
if ($room  === '') $missing[] = 'room';
if ($start === '') $missing[] = 'start';
if ($end   === '') $missing[] = 'end';

if (!empty($missing)) {
  echo "<h2>入力が不足しています</h2>";
  echo "<p>不足している項目：<b>" . h(implode(', ', $missing)) . "</b></p>";

  // デバッグ：実際に届いている中身を表示（原因特定用）
  echo "<h3>受信データ（REQUEST）</h3>";
  echo "<pre>" . h(print_r($_REQUEST, true)) . "</pre>";

  // 戻るリンク（入力を引き継ぐ）
  // dayselectへ戻る：日付だけ戻す
  echo '<p><a href="dayselect.html">日付選択へ戻る</a></p>';

  // selectroomへ戻る：日付を付けて戻す
  if ($date !== '') {
    echo '<p><a href="selectroom.php?date=' . urlencode($date) . '">教室選択へ戻る</a></p>';
  }

  // timeselectへ戻る：日付＋教室を付けて戻す
  if ($date !== '' && $room !== '') {
    echo '<p><a href="timeselect.php?date=' . urlencode($date) . '&room=' . urlencode($room) . '">時間選択へ戻る</a></p>';
  }

  exit;
}

// ここまで来たら入力は揃っている
$start_ts = strtotime("$date $start");
$end_ts   = strtotime("$date $end");

if ($start_ts === false || $end_ts === false) {
  echo "日付または時刻形式が不正です。";
  exit;
}
if ($end_ts <= $start_ts) {
  echo "終了時刻は開始時刻より後にしてください。";
  exit;
}
if ((($end_ts - $start_ts) % (15 * 60)) !== 0) {
  echo "15分単位で選択してください。";
  exit;
}

echo "予約内容：" . h($date) . " / " . h($room) . " / " . h($start) . "〜" . h($end);

// ここからDBの重複チェック→INSERT
?>
