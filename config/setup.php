<?php
require_once 'database.php';

$db = new PDO("mysql:host=127.0.0.1:3306", $DB_USER, $DB_PASSWORD);

$sql = file_get_contents('./config/db_camagru.sql');

$qr = $db->exec($sql);

header("Location:../index.php");
echo "Database has been created!";
?>