<?php
session_start();
$id = $_POST['id'];
$dsn = "mysql:host=localhost:23333; dbname=webdb; charset=utf8";
$username = "groupC1";
$password = "password";
try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$sql = "SELECT * FROM member WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();
$member = $stmt->fetch();
//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $member['pass'])) {
    //DBのユーザー情報をセッションに保存
    $_SESSION['id'] = $member['id'];
    $_SESSION['name'] = $member['name'];
    $_SESSION['role'] = $member['role'];
    $msg = 'ログインしました。';
    $link = '<a href="mainmenu.php">ホーム</a>';
} else {
    $msg = 'AXIAアカウントもしくはパスワードが間違っています。';
    $link = '<a href="login_form.php">戻る</a>';
}
?>

<h1><?php echo $msg; ?></h1>
<?php echo $link; ?>
