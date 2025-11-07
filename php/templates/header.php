<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCmarket</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>

    <?php if (isset($_SESSION) && isset($_SESSION['user_id'])): ?>
    <script>
      (function(){
        history.pushState(null, document.title, location.href);
        window.addEventListener('popstate', function () {
          history.pushState(null, document.title, location.href);
        });
      })();
    </script>
    <?php endif; ?>
    <div class="prospect-name-top">CCMarket System</div>
    <header>
        <a href="index.php" class="site-logo-link">
            <img src="images/ccmartlogo.svg" alt="CCmarket Logo" class="site-logo" onerror="this.onerror=null;this.src='images/ccmartlogo.jpg'">
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Log-out</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container"> <!-- Wrapper for content -->
