<?php
session_start();
require_once("MYDB.php");

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ログイン必須
if (!isset($_SESSION['id'])) {
  header("Location: login_form.php");
  exit;
}

$pdo = db_connect();
$id  = (string)$_SESSION['id'];

// 入力受け取り
$name = $_POST['name'] ?? '';
$pass = $_POST['pass'] ?? '';

$msg = "";

// 入力チェック
if ($name === '' || $pass === '') {
  $msg = "名前またはパスワードが未入力です。";
} else {
  // パスワードはハッシュ化
  $passHash = password_hash($pass, PASSWORD_DEFAULT);

  try {
    $pdo->beginTransaction();

    $sql = "UPDATE member
            SET pass = :pass,
                name = :name
            WHERE id = :id";
    $st = $pdo->prepare($sql);
    $st->bindValue(':pass', $passHash, PDO::PARAM_STR);
    $st->bindValue(':name', $name, PDO::PARAM_STR);
    $st->bindValue(':id',   $id,   PDO::PARAM_STR);

    $st->execute();
    $pdo->commit();

    $cnt = (int)$st->rowCount();
    $msg = "データを {$cnt} 件、更新しました。<br>セキュリティのため再ログインしてください。";

    // パスワード変更後はログアウト
    $_SESSION = [];
    session_destroy();

  } catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $msg = "エラー：" . h($e->getMessage());
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>メンバー情報更新</title>
</head>
<body>
  <h1>メンバー情報更新</h1>

  <p><?= $msg ?></p>

  <p>
    <a href="login_form.php">ログイン画面へ</a>
  </p>
</body>
</html>
