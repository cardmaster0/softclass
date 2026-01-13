<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<title>PHPのテスト</title>
</head>
<body>
<hr>
更新画面
<hr>
[ <a href="list.php">戻る</a>]
<br>

<?php
require_once("MYDB.php");
$pdo = db_connect();

if(isset($_GET['id']) && $_GET['id'] > 0){
    $id	= $_GET['id']; 
    $_SESSION['id'] = $id;
}else{
    exit('パラメータが不正です。');
}

try {
  $sql= "SELECT * FROM member WHERE id = :id ";
  $stmh = $pdo->prepare($sql);
  $stmh->bindValue(':id',  $id, PDO::PARAM_INT );
  $stmh->execute();
  $count = $stmh->rowCount();
  
} catch (PDOException $Exception) {
  print "エラー：" . $Exception->getMessage();
}

if($count < 1){
  print "更新データがありません。<br>";
}else{
  $row = $stmh->fetch(PDO::FETCH_ASSOC);  
?>
<form name="form1" method="post" action="list.php">
番号：<?=htmlspecialchars($row['id'], ENT_QUOTES)?><br>
氏：<input type="text" name="last_name" value="<?=htmlspecialchars($row['last_name'], ENT_QUOTES)?>"><br>
名：<input type="text" name="first_name" value="<?=htmlspecialchars($row['first_name'], ENT_QUOTES)?>"><br>
年齢：<input type="text" name="age" value="<?=htmlspecialchars($row['age'], ENT_QUOTES)?>"><br>
<input type="hidden" name="action" value="update">
<input type="submit" value="更新">
</form>
<?php
}
?>
</body>
</html>
