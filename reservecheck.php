<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login_form.php");
    exit();
}

$dsn = "mysql:host=localhost:23333; dbname=webdb; charset=utf8";
$username = "groupC1";
$password = "password";
try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$sql = "SELECT num, date, start_time, room FROM reservation WHERE id = ? ORDER BY date, time";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();


?>