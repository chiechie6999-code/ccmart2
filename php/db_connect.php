<?php
if (!extension_loaded('pdo_mysql')) {
    die("PDO MySQL extension is not enabled. Please check your php.ini configuration.");
}

$db_host = 'localhost';
$db_name = 'ccmart_db';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db_name :" . $e->getMessage());
}
?>
