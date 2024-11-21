<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and the path is correct

// Check if the user is logged in and has the 'bidder' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'bidder') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's bids and associated listing information
$sql = "
    SELECT b.bid_amount, b.bid_time, l.item_name, l.image_path, l.status
    FROM bids b
    JOIN listings l ON b.listing_id = l.listing_id
    WHERE b.user_id = ?
    ORDER BY b.bid_time DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bids</title>
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

    <div class="bids-container">
        <h1>My Bids</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="bids-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bid-card">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>">
                        <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                        <p>Bid Amount: ₹<?php echo htmlspecialchars($row['bid_amount']); ?></p>
                        <p>Bid Time: <?php echo htmlspecialchars($row['bid_time']); ?></p>
                        <p>Status: <?php echo htmlspecialchars(ucfirst($row['status'])); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You have not placed any bids yet. <a href="browse_listings.php">Browse listings</a> and place your first bid!</p>
        <?php endif; ?>
    </div>
</body>
</html>
