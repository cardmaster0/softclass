<?php
session_start(); // セッション開始

// サインイン状態を確認
if (isset($_SESSION['user'])) {
    // サインイン済み
    $username = $_SESSION['user'];
} else {
    // 未サインイン
    $username = null;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>トップページ</title>
</head>
<body>
    <header>
        <h1>My Website</h1>
        <nav>
            <ul>
                <li><a href="index.php">ホーム</a></li>
                <li><a href="about.php">会社概要</a></li>
                <li><a href="contact.php">お問い合わせ</a></li>
                <?php if ($username): ?>
                    <li><a href="logout.php">ログアウト</a></li>
                <?php else: ?>
                    <li><a href="login.php">ログイン</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($username): ?>
            <h2>ようこそ、<?php echo htmlspecialchars($username); ?> さん！</h2>
            <p>あなたはサインインしています。</p>
        <?php else: ?>
            <h2>ゲストとして閲覧中</h2>
            <p><a href="login.php">ログイン</a>してください。</p>
        <?php endif; ?>
    </main>
</body>
</html>

