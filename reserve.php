<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ログイン必須（login.php で $_SESSION['id'] をセットしている前提）
if (!isset($_SESSION['id'])) {
  header("Location: login_form.php");
  exit;
}

$userId = (string)$_SESSION['id']; // reservation.id (varchar10) に入れる

// ---------- 入力（GET or POST） ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $date  = $_POST['date']  ?? '';
  $room  = $_POST['room']  ?? '';
  $start = $_POST['start'] ?? '';
  $end   = $_POST['end']   ?? '';
} else {
  $date  = $_GET['date']  ?? '';
  $room  = $_GET['room']  ?? '';
  $start = $_GET['start'] ?? '';
  $end   = $_GET['end']   ?? '';
}

// 不足チェック
$missing = [];
if ($date  === '') $missing[] = 'date';
if ($room  === '') $missing[] = 'room';
if ($start === '') $missing[] = 'start';
if ($end   === '') $missing[] = 'end';

if (!empty($missing)) {
  ?>
  <!DOCTYPE html>
  <html lang="ja"><head>
    <meta charset="UTF-8"><title>予約確認</title>
  </head><body>
    <h2>入力が不足しています</h2>
    <p>不足している項目：<b><?= h(implode(', ', $missing)) ?></b></p>

    <p><a href="dayselect.php">日付選択へ戻る</a></p>

    <?php if ($date !== ''): ?>
      <p><a href="selectroom.php?date=<?= urlencode($date) ?>">教室選択へ戻る</a></p>
    <?php endif; ?>

    <?php if ($date !== '' && $room !== ''): ?>
      <p><a href="timeselect.php?date=<?= urlencode($date) ?>&room=<?= urlencode($room) ?>">時間選択へ戻る</a></p>
    <?php endif; ?>
  </body></html>
  <?php
  exit;
}

// ---------- 時刻の妥当性チェック ----------
$start_ts = strtotime("$date $start");
$end_ts   = strtotime("$date $end");
$now = time();

if($start_ts<$now){
    $err = "過去の日時は予約できません！！"
}

if ($start_ts === false || $end_ts === false) {
  $err = "日付または時刻形式が不正です";
} elseif ($end_ts <= $start_ts) {
  $err = "終了時刻は開始時刻より後にしてください";
} else {
  $diff = $end_ts - $start_ts;
  if ($diff % (15 * 60) !== 0) {
    $err = "15分単位で選択してください";
  }
}

if (isset($err)) {
  ?>
  <!DOCTYPE html>
  <html lang="ja"><head>
    <meta charset="UTF-8"><title>予約確認</title>
  </head><body>
    <h2><?= h($err) ?></h2>
    <p><a href="timeselect.php?date=<?= urlencode($date) ?>&room=<?= urlencode($room) ?>">時間選択へ戻る</a></p>
  </body></html>
  <?php
  exit;
}

// ---------- DB接続 ----------
$dsn = "mysql:host=localhost:23333; dbname=webdb; charset=utf8";
$dbUser = "groupC1";
$dbPass = "password";

$dbError = null;
try {
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  $dbError = $e->getMessage();
}

// ---------- POST のときだけ「重複チェック→INSERT」 ----------
$insertMsg = "";
$done = (($_GET['done'] ?? '') === '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$dbError) {
  try {
    $pdo->beginTransaction();

    // 重複（時間帯の重なり）チェック
    // 既存.start_time < 新.end_time AND 既存.end_time > 新.start_time
    $sqlDup = "
      SELECT COUNT(*) AS cnt
      FROM reservation
      WHERE date = :date
        AND room = :room
        AND start_time < :new_end
        AND end_time > :new_start
    ";
    $st = $pdo->prepare($sqlDup);
    $st->execute([
      ':date' => $date,
      ':room' => $room,
      ':new_start' => $start,
      ':new_end' => $end,
    ]);

    $dup = (int)($st->fetch()['cnt'] ?? 0);

    if ($dup > 0) {
      $pdo->rollBack();
      $insertMsg = "その時間帯は既に予約されています。別の時間を選んでください。";
    } else {
      // INSERT（num は auto_increment なので指定しない）
      $sqlIns = "
        INSERT INTO reservation (date, start_time, end_time, room, id)
        VALUES (:date, :start_time, :end_time, :room, :id)
      ";
      $st2 = $pdo->prepare($sqlIns);
      $st2->execute([
        ':date' => $date,
        ':start_time' => $start,
        ':end_time' => $end,
        ':room' => $room,
        ':id' => $userId,
      ]);

      $pdo->commit();

      // 二重送信防止（PRG）
      header(
        "Location: reserve.php?done=1"
        . "&date="  . urlencode($date)
        . "&room="  . urlencode($room)
        . "&start=" . urlencode($start)
        . "&end="   . urlencode($end)
      );
      exit;
    }
  } catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) $pdo->rollBack();
    $insertMsg = "予約登録に失敗しました: " . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>予約確認</title>
</head>
<body>

<h2>予約内容</h2>
<ul>
  <li>利用者ID：<?= h($userId) ?></li>
  <li>日付：<?= h($date) ?></li>
  <li>教室：<?= h($room) ?></li>
  <li>時間：<?= h($start) ?> ～ <?= h($end) ?></li>
</ul>

<?php if ($dbError): ?>
  <h3>DB接続エラー</h3>
  <p><?= h($dbError) ?></p>

<?php elseif ($done): ?>
  <h3>予約を登録しました</h3>
  <p><a href="mainmenu.php">ホームに戻る</a></p>
  <p><a href="reservecheck.php">予約一覧を見る</a></p>

<?php else: ?>
  <?php if ($insertMsg !== ''): ?>
    <p style="color:red;"><?= h($insertMsg) ?></p>
  <?php endif; ?>

  <!-- 確定ボタン（POSTでINSERT） -->
  <form method="post" action="reserve.php">
    <input type="hidden" name="date"  value="<?= h($date) ?>">
    <input type="hidden" name="room"  value="<?= h($room) ?>">
    <input type="hidden" name="start" value="<?= h($start) ?>">
    <input type="hidden" name="end"   value="<?= h($end) ?>">
    <button type="submit">この内容で予約する</button>
  </form>
<?php endif; ?>

<hr>

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



