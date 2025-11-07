<?php
session_start();
require_once 'php/templates/header-auth.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<div class="container">
    <h2>Forgot Password</h2>
    <p>Please enter your ID Number to start the password recovery process.</p>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="php/forgot_password_process.php" method="post">
        <div class="form-group">
            <label for="id_number">ID Number <span class="required">*</span></label>
            <input type="text" name="id_number" id="id_number" placeholder="xxxx-xxxx" required>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>

<?php require_once 'templates/footer.php'; ?>
