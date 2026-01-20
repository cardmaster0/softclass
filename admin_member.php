<?php
session_start();
if (!isset($_SESSION['id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: mainmenu.php");
  exit();
}

require_once("MYDB.php");
$pdo = db_connect();

// --- 削除処理（member + 予約） ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && $_GET['id'] !== '') {
  $delId = (string)$_GET['id'];

  // 自分自身は削除させない（事故防止）
  if ($delId === (string)($_SESSION['id'] ?? '')) {
    $msg = "自分自身は削除できません。";
  } else {
    try {
      $pdo->beginTransaction();

      // ① 予約削除
      $st1 = $pdo->prepare("DELETE FROM reservation WHERE id = :id");
      $st1->bindValue(':id', $delId, PDO::PARAM_STR);
      $st1->execute();
      $deletedRes = $st1->rowCount();

      // ② member削除
      $st2 = $pdo->prepare("DELETE FROM member WHERE id = :id");
      $st2->bindValue(':id', $delId, PDO::PARAM_STR);
      $st2->execute();
      $deletedMem = $st2->rowCount();

      $pdo->commit();
      $msg = "ユーザー削除: {$deletedMem}件 / 予約削除: {$deletedRes}件";
    } catch (PDOException $e) {
      $pdo->rollBack();
      $msg = "エラー: " . $e->getMessage();
    }
  }
}

// --- 一覧取得 ---
$stmh = $pdo->query("SELECT id, name, role FROM member ORDER BY role DESC, id ASC");
$count = $stmh->rowCount();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理者画面（ユーザー管理）</title>
</head>
<body>

<hr>
管理者メニュー（ユーザー管理）
<hr>

<?php if (!empty($msg)): ?>
  <p><?= htmlspecialchars($msg, ENT_QUOTES) ?></p>
<?php endif; ?>

<p>検索結果は <?= (int)$count ?> 件です。</p>

<?php if ($count < 1): ?>
  <p>検索結果がありません。</p>
<?php else: ?>
<table border="1">
  <tbody>
    <tr><th>AXIAアカウント</th><th>名前</th><th>管理者or利用者</th><th>操作</th></tr>
    <?php while ($row = $stmh->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?= htmlspecialchars($row['id'], ENT_QUOTES) ?></td>
        <td><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
        <td><?= htmlspecialchars($row['role'], ENT_QUOTES) ?></td>
        <td>
          <?php if ($row['id'] === ($_SESSION['id'] ?? '')): ?>
            （自分）
          <?php else: ?>
            <a href="admin_member.php?action=delete&id=<?= urlencode($row['id']) ?>"
               onclick="return confirm('ユーザーと予約を削除します。本当によろしいですか？');">削除</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php endif; ?>

<p><a href="mainmenu.php">戻る</a></p>
</body>
</html>
