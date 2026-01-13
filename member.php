<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<title>メンバー管理</title>
</head>
<body>
<?php
require_once("MYDB.php");
$pdo = db_connect();

// セッション変数から受け取ります。
$id  = $_SESSION['id'];
passHash = password_hash($pass,PASSWORD_DEFAULT);
try {
  $pdo->beginTransaction();
  $sql = "UPDATE  member
            SET 
            pass  = :pass,
              name = :name
          WHERE id = :id";
  $stmh = $pdo->prepare($sql);
  $stmh->bindValue(':pass',  $_POST['pass'],  PDO::PARAM_STR );
  $stmh->bindValue(':name', $_POST['name'], PDO::PARAM_STR );
    $stmh->bindValue(':id', $_POST['id'], PDO::PARAM_STR );
  $stmh->execute();
  $pdo->commit();
  print "データを" . $stmh->rowCount() . "件、更新しました。<br>";
  
} catch (PDOException $Exception) {
  $pdo->rollBack();
  print "エラー：" . $Exception->getMessage();
}

// セッション変数を全て解除する
$_SESSION = array();

// 最終的に、セッションを破壊する
session_destroy();


?>
</body>
</html>
