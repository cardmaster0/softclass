<!DOCTYPE html>
<html>
    <head>
        <title>ログインページ</title>
    </head>
    <body>
        <h1>ログインページ</h1>
        <form action="login.php" method="post">
            <div>
                <label>AXIAアカウント：<input type="text" name="id" required></label>
            </div>
            <div>
                <label>パスワード：<input type="password" name="pass" required></label>
            </div>
            <input type="submit" value="ログイン">
        </form>
    </body>
</html>
