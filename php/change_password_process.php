<?php
session_start();
require_once 'db_connect.php';

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_SESSION['password_reset_authorized']) ||
    !$_SESSION['password_reset_authorized'] ||
    !isset($_SESSION['password_reset_id_number'])
) {
    // Unauthorized access
    header('Location: ../forgot_password.php');
    exit();
}

$password = $_POST['password'];
$re_password = $_POST['re_password'];
$id_number = $_SESSION['password_reset_id_number'];

// Validation
if (empty($password) || empty($re_password)) {
    $_SESSION['error'] = 'Please fill in both password fields.';
    header('Location: ../change_password.php');
    exit();
}

if ($password !== $re_password) {
    $_SESSION['error'] = 'Passwords do not match.';
    header('Location: ../change_password.php');
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['error'] = 'Password must be at least 8 characters long.';
    header('Location: ../change_password.php');
    exit();
}

// All checks passed, update the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id_number = ?");
    $stmt->execute([$hashed_password, $id_number]);

    // Clean up session
    unset(
        $_SESSION['password_reset_id_number'],
        $_SESSION['password_reset_authorized']
    );

    $_SESSION['success_message'] = 'Your password has been successfully changed. Please log in.';
    header('Location: ../login.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = 'An error occurred while changing your password. Please try again.';
    // For debugging: error_log($e->getMessage());
    header('Location: ../change_password.php');
    exit();
}
?>
