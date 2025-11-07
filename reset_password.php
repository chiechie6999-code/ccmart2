<?php
session_start();
require_once 'php/db_connect.php';
require_once 'php/templates/header-auth.php';

// Ensure user has passed the ID check
if (!isset($_SESSION['password_reset_id_number'])) {
    header('Location: forgot_password.php');
    exit();
}

$id_number = $_SESSION['password_reset_id_number'];

// Fetch the user's questions from the database
// In a real application, you would have a table mapping question IDs to question text.
// For this project, we'll use a hardcoded array based on the registration form.
$questions = [
    1 => 'Who is your best friend in Elementary?',
    2 => 'What is the name of your favorite pet?',
    3 => 'Who is your favorite teacher in high school?'
];

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<div class="container">
    <h2>Answer Security Questions</h2>
    <p>Please answer the following questions to verify your identity.</p>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="php/reset_password_process.php" method="post">
        <div class="form-group">
            <label><?php echo $questions[1]; ?></label>
            <div class="password-field">
                <input type="password" name="answer1" id="answer1" required>
                <button type="button" class="toggle-password" data-target="answer1"><i class="fa fa-eye"></i></button>
            </div>
            <label>Re-enter Answer:</label>
            <div class="password-field">
                <input type="password" name="re_answer1" id="re_answer1" required>
                <button type="button" class="toggle-password" data-target="re_answer1"><i class="fa fa-eye"></i></button>
            </div>
        </div>
        <div class="form-group">
            <label><?php echo $questions[2]; ?></label>
            <div class="password-field">
                <input type="password" name="answer2" id="answer2" required>
                <button type="button" class="toggle-password" data-target="answer2"><i class="fa fa-eye"></i></button>
            </div>
            <label>Re-enter Answer:</label>
            <div class="password-field">
                <input type="password" name="re_answer2" id="re_answer2" required>
                <button type="button" class="toggle-password" data-target="re_answer2"><i class="fa fa-eye"></i></button>
            </div>
        </div>
        <div class="form-group">
            <label><?php echo $questions[3]; ?></label>
            <div class="password-field">
                <input type="password" name="answer3" id="answer3" required>
                <button type="button" class="toggle-password" data-target="answer3"><i class="fa fa-eye"></i></button>
            </div>
            <label>Re-enter Answer:</label>
            <div class="password-field">
                <input type="password" name="re_answer3" id="re_answer3" required>
                <button type="button" class="toggle-password" data-target="re_answer3"><i class="fa fa-eye"></i></button>
            </div>
        </div>
        <button type="submit">Verify Answers</button>
    </form>
</div>

<?php require_once 'templates/footer.php'; ?>
