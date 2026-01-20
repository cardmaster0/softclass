<?php
session_start();
if (!isset($_SESSION['id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: mainmenu.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>管理者画面</title></head>
<body>

<hr>
管理者メニュー
<hr>

<?php
require_once("MYDB.php");
$pdo = db_connect();

// 削除処理
if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0 ){
    try {
      $pdo->beginTransaction();
      $id = $_GET['id'];
      $sql = "DELETE FROM member WHERE id = :id";
      $stmh = $pdo->prepare($sql);
      $stmh->bindValue(':id', $id, PDO::PARAM_INT );
      $stmh->execute();
      $pdo->commit();
      print "データを" . $stmh->rowCount() . "件、削除しました。<br>";

    } catch (PDOException $Exception) {
      $pdo->rollBack();
      print "エラー：" . $Exception->getMessage();
    }
}

// 検索および現在の全データを表示します
try {
  
    $sql= "SELECT id,name,role FROM member";
    $stmh = $pdo->query($sql);
  
  $count = $stmh->rowCount();
  print "検索結果は" . $count . "件です。<br>";

} catch (PDOException $Exception) {
  print "エラー：" . $Exception->getMessage();
}


if($count < 1){
	print "検索結果がありません。<br>";
}else{
?>
<table border="1">
<tbody>
<tr><th>AXIAアカウント</th><th>名前</th><th>管理者or利用者</th><th>&nbsp;</th></tr>
<?php
  while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
?>
<tr>
<td><?=htmlspecialchars($row['id'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['name'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['role'], ENT_QUOTES)?></td>
<td><a href=admin_member.php?action=delete&id=<?=htmlspecialchars($row['id'], ENT_QUOTES)?>>削除</a></td>
</tr>
<?php
}    
?>
</tbody></table>

<?php
}

?>

<p><a href="mainmenu.php">戻る</a></p>

</body>
</html>