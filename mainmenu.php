<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ホーム</title>
    </head>
    <body>
        <h1>ホーム画面</h1>
        <p>ようこそ、<?php echo $_SESSION['name']; ?> さん!</p>
        <a href="dayselect.php">教室予約</a><br>
        <a href="reservecheck.php">予約確認</a><br>
        <a href="logout.php">サインアウト</a><br>
    </body>
</html>