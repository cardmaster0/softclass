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

<form name="form1" method="post" action="admin_dashboard.php">
日付：<input type="date" name="search_key"><input type="submit" value="検索する">
</form>

<?php
require_once("MYDB.php");
$pdo = db_connect();

// 削除処理
if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['num'] > 0 ){
    try {
      $pdo->beginTransaction();
      $num = $_GET['num'];
      $sql = "DELETE FROM reservation WHERE num = :num";
      $stmh = $pdo->prepare($sql);
      $stmh->bindValue(':num', $num, PDO::PARAM_INT );
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
  if(isset($_POST['search_key']) && $_POST['search_key'] != ""){
    $search_key = $_POST['search_key']; 
    $sql= "SELECT num,date,room,start_time,end_time,id FROM reservation WHERE date = :date";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':date',  $search_key, PDO::PARAM_STR);
    $stmh->execute();
  }else{
    $sql= "SELECT num,date,room,start_time,end_time,id FROM reservation";
    $stmh = $pdo->query($sql);
  }
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
<tr><th>予約番号</th><th>日にち</th><th>教室</th><th>開始時刻</th><th>終了時刻</th><th>AXIAアカウント</th><th>&nbsp;</th></tr>
<?php
  while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
?>
<tr>
<td><?=htmlspecialchars($row['num'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['date'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['room'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['start_time'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['end_time'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['id'], ENT_QUOTES)?></td>
<td><a href=admin_dashboard.php?action=delete&num=<?=htmlspecialchars($row['num'], ENT_QUOTES)?>>削除</a></td>
</tr>
<?php
}    
?>
</tbody></table>

<?php
}

?>

<a href="mainmenu.php">戻る</a>
</body>
</html>
