<?php
session_start();
require_once("MYDB.php");
$pdo = db_connect();

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ログイン必須
if (!isset($_SESSION['id'])) {
  header("Location: login_form.php");
  exit;
}

$id = (string)$_SESSION['id'];

try {
  $sql= "SELECT id, name FROM member WHERE id = :id";
  $st = $pdo->prepare($sql);
  $st->bindValue(':id', $id, PDO::PARAM_STR);
  $st->execute();
  $row = $st->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    exit('ユーザーが存在しません。');
  }
} catch (PDOException $e) {
  exit("エラー：" . h($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>更新画面</title>
</head>
<body>
<hr>更新画面<hr>
[ <a href="mainmenue.php">戻る</a> ]
<br>

<form method="post" action="member_update.php">
  ID：<?= h($row['id']) ?><br>
  名前：<input type="text" name="name" value="<?= h($row['name']) ?>" required><br>

  新しいパスワード：<input type="password" name="pass" required><br>
  <small>※現在のパスワードは表示しません</small><br>

  <input type="submit" value="更新">
</form>

</body>
</html>
