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
        <a href="member_form.php">会員情報変更</a><br>

        <?php if (($_SESSION['role']?? '')==='admin'):?>
            <p><a href="admin_dashboard.php">管理者メニュー</a></p>
            <?php endif; ?>
        <p><a href="logout.php">サインアウト</a></p>
    </body>
</html>