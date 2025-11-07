<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../forgot_password.php');
    exit();
}

$id_number = trim($_POST['id_number']);

if (empty($id_number)) {
    $_SESSION['error'] = 'ID Number is required.';
    header('Location: ../forgot_password.php');
    exit();
}

$stmt = $pdo->prepare("SELECT id_number FROM users WHERE id_number = ?");
$stmt->execute([$id_number]);
$user = $stmt->fetch();

if ($user) {
    // User found, store ID in session and proceed to question step
    $_SESSION['password_reset_id_number'] = $user['id_number'];
    header('Location: ../reset_password.php');
    exit();
} else {
    // User not found
    $_SESSION['error'] = 'No account found with that ID Number.';
    header('Location: ../forgot_password.php');
    exit();
}
?>
