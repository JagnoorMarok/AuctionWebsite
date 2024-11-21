<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and the path is correct

// Fetch active listings from the database
$sql = "SELECT listing_id, item_name, description, start_bid, image_path, created_at 
        FROM listings 
        WHERE status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Listings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <a href="index.html" class="logo">TopBid</a>
        <div class="nav-buttons">
            <a href="bid-board.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="listings-container">
        <h1>Browse Active Listings</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="listings-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="listing-card" onclick="openListingDetails(<?php echo $row['listing_id']; ?>)">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>">
                        <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p>Start Bid: ₹<?php echo htmlspecialchars($row['start_bid']); ?></p>
                        <p>Created At: <?php echo htmlspecialchars($row['created_at']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No active listings available. <a href="add_listing.php">Add a new listing</a> now!</p>
        <?php endif; ?>
    </div>

    <script>
        function openListingDetails(listingId) {
            window.location.href = "listing_details.php?listing_id=" + listingId;
        }
    </script>
</body>
</html>
