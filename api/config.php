<?php
$host = '127.0.0.1';
$db_name = 'cake_db';
$username = 'root';
$password = '';

date_default_timezone_set('Asia/Manila');

try {
    $conn = new PDO("mysql:host=" . $host, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
    $conn->exec("USE `$db_name`");
} catch(PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
    exit;
}
?>
