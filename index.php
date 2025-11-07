<?php
session_start();

// If the user is not logged in, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

require_once 'php/templates/header.php';
?>
<script>
    (function(){
        history.pushState(null, document.title, location.href);
        window.addEventListener('popstate', function () {
            history.pushState(null, document.title, location.href);
        });
    })();
</script>

<h1>Welcome to the Home Page, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
<p>You are successfully logged in.</p>
<p>This is the main content area for logged-in users.</p>

<?php
require_once 'templates/footer.php';
?>
