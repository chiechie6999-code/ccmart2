<?php
require_once 'db_connect.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        echo 'taken';
    } else {
        echo 'available';
    }
}
?>
