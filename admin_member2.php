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
// 削除処理（ユーザー削除＋予約削除）
if (
    isset($_GET['action'], $_GET['id'])
    && $_GET['action'] === 'delete'
    && $_GET['id'] !== ''
) {
    try {
        $pdo->beginTransaction();

        $id = (string)$_GET['id']; // AXIAアカウント等の文字列想定

        // ① そのユーザーの予約をすべて削除（reservation.id）
        $sql1 = "DELETE FROM reservation WHERE id = :id";
        $st1 = $pdo->prepare($sql1);
        $st1->bindValue(':id', $id, PDO::PARAM_STR);
        $st1->execute();
        $deletedReserve = $st1->rowCount();

        // ② ユーザー削除（member.id）
        $sql2 = "DELETE FROM member WHERE id = :id";
        $st2 = $pdo->prepare($sql2);
        $st2->bindValue(':id', $id, PDO::PARAM_STR);
        $st2->execute();
        $deletedMember = $st2->rowCount();

        $pdo->commit();

        print "ユーザーを {$deletedMember} 件削除しました。<br>";
        print "関連する予約を {$deletedReserve} 件削除しました。<br>";

    } catch (PDOException $Exception) {
        if ($pdo->inTransaction()) $pdo->rollBack();
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
