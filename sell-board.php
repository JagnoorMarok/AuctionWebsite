<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in and has the 'seller' role
if (isset($_SESSION['username']) && $_SESSION['role'] === 'seller') {
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
    <title>Seller Dashboard - TopBid</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation bar -->
    <header class="navbar">
        <a href="index.html" class="logo">TopBid</a>
        <div class="nav-buttons">
            <a href="logout.php">Logout</a>
            <a href="sell-board.php">Dashboard</a>
        </div>
    </header>
    
    <!-- Seller Dashboard Content -->
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Here you can manage your listings, view bids, and more.</p>

        <!-- Button to add new listing -->
        <a href="add_listing.php">
            <button class="action-btn">Create New Listing</button>
        </a>
        <a href="view_my_listing.php">
            <button class="action-btn">View My Listings</button>
        </a>
        <a href="view_bidding_history.php">
            <button class="action-btn">Bidding History</button>
        </a>
    </div>
</body>
</html>
