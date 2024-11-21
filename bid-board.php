<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in and has the 'bidder' role
if (isset($_SESSION['username']) && $_SESSION['role'] === 'bidder') {
    $username = $_SESSION['username'];
} else {
    // Redirect to login page if not authenticated or if role mismatch
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidder Dashboard - TopBid</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation bar -->
    <header class="navbar">
        <a href="index.html" class="logo">TopBid</a>
        <div class="nav-buttons">
            <a href="logout.php">Logout</a>
            <a href="bid-board.php">Dashboard</a>
        </div>
    </header>
    
    <!-- Bidder Dashboard Content -->
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Here you can browse active listings and place bids.</p>

        
        <a href="browse_listings.php">
            <button class="action-btn">Browse Listings</button>
        </a>
        
        <a href="view_my_bids.php">
        <button class="action-btn">View My Bids</button>
        </a>
        <a href="view_won_auctions.php">
        <button class="action-btn">View Won Auctions</button>
        </a>
        
    </div>
</body>
</html>
