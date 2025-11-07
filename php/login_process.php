<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit();
}

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Check if user is locked out
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    $remaining = $_SESSION['lockout_time'] - time();
    $_SESSION['errors']['login'] = "Too many failed login attempts. Please try again in {$remaining} seconds.";
    header('Location: ../login.php');
    exit();
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    $_SESSION['errors']['login'] = 'Username and Password are required.';
    $_SESSION['input']['username'] = $username;
    header('Location: ../login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Correct credentials
    $_SESSION['login_attempts'] = 0;
    unset($_SESSION['lockout_time']);

    session_regenerate_id(true); // Prevent session fixation
    $_SESSION['user_id'] = $user['id_number'];
    $_SESSION['username'] = $user['username'];

    header("Location: ../index.php");
    exit();
} else {
    // Incorrect credentials
    $_SESSION['login_attempts']++;
    $attempts = $_SESSION['login_attempts'];
    $lockout_duration = 0;

    if ($attempts >= 9) {
        $lockout_duration = 60;
    } elseif ($attempts >= 6) {
        $lockout_duration = 30;
    } elseif ($attempts >= 3) {
        $lockout_duration = 15;
    }

    if ($lockout_duration > 0) {
        $_SESSION['lockout_time'] = time() + $lockout_duration;
        $_SESSION['errors']['login'] = "Too many failed login attempts. Please try again in {$lockout_duration} seconds.";
    } else {
        $_SESSION['errors']['login'] = 'Invalid username or password.';
    }

    $_SESSION['input']['username'] = $username;
    header('Location: ../login.php');
    exit();
}
?>
