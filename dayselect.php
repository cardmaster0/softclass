<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>教室予約</title>

    <link rel="stylesheet" href="./style.css" />
    <script src="./script.js" defer></script>
  </head>

  <body>
    <form action="reserve.php" method="get">
      <div>
        <label>日付</label>
        <input type="date" id="inputDate" name="date" required />
      </div>

      <div>
        <label>時間</label>
        <input type="time" id="inputTime" name="time" required />
      </div>

      <div>
        <label>教室</label>
        <select id="room" name="room" required>
          <option value="" selected disabled>教室を選択</option>
          <option value="A101">A101</option>
          <option value="A102">A102</option>
          <option value="B201">B201</option>
          <option value="B202">B202</option>
        </select>
      </div>

      <button type="submit">予約へ進む</button>
    </form>
  </body>
</html>
