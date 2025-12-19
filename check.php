<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// DB接続情報
$host = "localhost";
$user = "db_user";
$pass = "db_password";
$dbname = "reservation_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// ログイン中のユーザー名を取得
$currentUser = $_SESSION['user'];

// SQL: ログインユーザーの予約だけ取得
$stmt = $conn->prepare("SELECT id, classroom, reserved_by, date, time 
                        FROM reservations 
                        WHERE reserved_by = ? 
                        ORDER BY date, time");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>予約確認</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($currentUser); ?> さんの予約一覧</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>教室</th>
            <th>予約者</th>
            <th>日付</th>
            <th>時間</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["classroom"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reserved_by"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["time"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>予約はありません</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="home.php">ホームに戻る</a>
</body>
</html>
<?php
$conn->close();
?>
