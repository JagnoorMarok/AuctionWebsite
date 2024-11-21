<?php
session_start();

// Destroy the session to log the user out
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - TopBid</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Logout Message -->
    <div class="logout-container">
        <h2>You have successfully logged out.</h2>
        <p>If you wish to log back in, please click the button below.</p>
        <a href="login.html" class="login-btn">Login Again</a>
    </div>
</body>
</html>
