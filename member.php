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

// 入力チェック
$name = $_POST['name'] ?? '';
$pass = $_POST['pass'] ?? '';

if ($name === '' || $pass === '') {
  echo "名前またはパスワードが未入力です。";
  exit;
}

// パスワードはハッシュ化（password_verify と整合）
$passHash = password_hash($pass, PASSWORD_DEFAULT);

try {
  $pdo->beginTransaction();

  $sql = "UPDATE member
          SET pass = :pass,
              name = :name
          WHERE id = :id";
  $stmh = $pdo->prepare($sql);
  $stmh->bindValue(':pass', $passHash, PDO::PARAM_STR);
  $stmh->bindValue(':name', $name, PDO::PARAM_STR);
  $stmh->bindValue(':id', $id, PDO::PARAM_STR);

  $stmh->execute();
  $pdo->commit();

  echo "データを " . (int)$stmh->rowCount() . " 件、更新しました。<br>";

  // 仕様：パスワード変更後はログアウトさせる（安全）
  $_SESSION = [];
  session_destroy();
  echo '<a href="login_form.php">再ログイン</a>';

} catch (PDOException $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo "エラー：" . h($e->getMessage());
}
?>
