<?php
function send_registration_email($email, $username) {
    // This is a placeholder function. In a real application, you would use a library like PHPMailer to send an email.
    $message = "---- REGISTRATION EMAIL ----\n";
    $message .= "TO: " . $email . "\n";
    $message .= "SUBJECT: Welcome to CCMarket!\n";
    $message .= "BODY: Hello " . $username . ",\n\nThank you for registering at CCMarket.\n";
    $message .= "--------------------------\n\n";

    // Log the email to a file instead of sending it.
    file_put_contents(__DIR__ . '/../email_log.txt', $message, FILE_APPEND);
}

function send_password_reset_email($email, $reset_link) {
    // This is a placeholder function.
    $message = "---- PASSWORD RESET EMAIL ----\n";
    $message .= "TO: " . $email . "\n";
    $message .= "SUBJECT: Password Reset Request\n";
    $message .= "BODY: Please click the following link to reset your password: " . $reset_link . "\n";
    $message .= "----------------------------\n\n";

    file_put_contents(__DIR__ . '/../email_log.txt', $message, FILE_APPEND);
}
?>
