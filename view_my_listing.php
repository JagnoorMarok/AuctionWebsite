<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and the path is correct

// Check if the user is logged in and has the 'seller' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Fetch the seller's listings from the database
$sql = "SELECT listing_id, item_name, description, start_bid, image_path, created_at FROM listings WHERE seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <a href="index.html" class="logo">TopBid</a>
        <div class="nav-buttons">
            <a href="sell-board.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="listings-container">
        <h1>My Listings</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="listings-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="#" class="listing-card">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?> Image">
                        <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p>Start Bid: ₹<?php echo htmlspecialchars($row['start_bid']); ?></p>
                        <p>Created At: <?php echo htmlspecialchars($row['created_at']); ?></p>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You have no listings. <a href="add_listing.php">Add a new listing</a> now!</p>
        <?php endif; ?>
    </div>
</body>
</html>
