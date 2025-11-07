<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCmarket</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body class="auth-page <?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'login-page' : (basename($_SERVER['PHP_SELF']) === 'register.php' ? 'register-page' : ''); ?>">
    <div class="prospect-name-top">CCMarket System</div>
    <header>
        <a href="index.php" class="site-logo-link">
            <img src="images/ccmartlogo.svg" alt="CCmarket Logo" class="site-logo" onerror="this.onerror=null;this.src='images/ccmartlogo.jpg'">
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php
                // session_start(); // Ensure session is started
                $current_page = basename($_SERVER['PHP_SELF']);
                $logged_in = isset($_SESSION['user_id']);

                if ($logged_in) {
                    echo '<li><a href="logout.php">Log-out</a></li>';
                } else {
                    if ($current_page === 'login.php') {
                        echo '<li><a href="register.php">Register</a></li>';
                    } elseif ($current_page === 'register.php') {
                        echo '<li><a href="login.php">Log-in</a></li>';
                    } else {
                        echo '<li><a href="login.php">Log-in</a></li>';
                        echo '<li><a href="register.php">Register</a></li>';
                    }
                }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
