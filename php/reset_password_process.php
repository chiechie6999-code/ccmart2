<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['password_reset_id_number'])) {
    header('Location: ../forgot_password.php');
    exit();
}

$id_number = $_SESSION['password_reset_id_number'];

$answer1 = trim($_POST['answer1']);
$re_answer1 = trim($_POST['re_answer1']);
$answer2 = trim($_POST['answer2']);
$re_answer2 = trim($_POST['re_answer2']);
$answer3 = trim($_POST['answer3']);
$re_answer3 = trim($_POST['re_answer3']);

// Check if answers match re-entered answers
if ($answer1 !== $re_answer1 || $answer2 !== $re_answer2 || $answer3 !== $re_answer3) {
    $_SESSION['error'] = 'The entered answers do not match the re-entered answers.';
    header('Location: ../reset_password.php');
    exit();
}

// Fetch correct answers from the database
$stmt = $pdo->prepare("SELECT question_id, answer FROM auth_answers WHERE user_id_number = ? ORDER BY question_id ASC");
$stmt->execute([$id_number]);
$correct_answers_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($correct_answers_raw) < 3) {
    // This should not happen if the user registered correctly
    $_SESSION['error'] = 'Could not retrieve security questions. Please contact support.';
    header('Location: ../forgot_password.php');
    exit();
}

// Format the fetched answers into a simple array
$correct_answers = [];
foreach ($correct_answers_raw as $row) {
    $correct_answers[$row['question_id']] = $row['answer'];
}

// Verify hashed answers
if (
    password_verify($answer1, $correct_answers[1]) &&
    password_verify($answer2, $correct_answers[2]) &&
    password_verify($answer3, $correct_answers[3])
) {
    // Answers are correct
    $_SESSION['password_reset_authorized'] = true;
    header('Location: ../change_password.php');
    exit();
} else {
    // Answers are incorrect
    $_SESSION['error'] = 'One or more answers are incorrect.';
    header('Location: ../reset_password.php');
    exit();
}
?>
