<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>日時選択</title>
  </head>

  <body>
    <form action="selectroom.php" method="get">
      <div>
        <label>日付</label>
        <input type="date" name="date" required />
      </div>

      <div>
        <label>時間</label>
        <input type="time" name="time" required />
      </div>

      <button type="submit">教室選択へ</button>
    </form>
  </body>
</html>
