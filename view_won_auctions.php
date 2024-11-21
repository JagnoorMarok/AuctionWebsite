<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'bidder' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'bidder') {
    header("Location: login.php");
    exit();
}

$bidder_id = $_SESSION['user_id'];

// Fetch the won auctions for the bidder
$sql = "
    SELECT wa.won_id, l.item_name, l.image_path, wa.final_bid_amount, wa.won_date, u.username AS seller_username
    FROM won_auctions wa
    JOIN listings l ON wa.listing_id = l.listing_id
    JOIN users u ON l.seller_id = u.user_id
    WHERE wa.bidder_id = ? 
    ORDER BY wa.won_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bidder_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Won Auctions</title>
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

    <div class="won-auctions-container">
        <h1>Your Won Auctions</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="won-auctions-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="won-auction-card">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>">
                        <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                        <p><strong>Seller:</strong> <?php echo htmlspecialchars($row['seller_username']); ?></p>
                        <p><strong>Final Bid Amount:</strong> ₹<?php echo htmlspecialchars($row['final_bid_amount']); ?></p>
                        <p><strong>Won Date:</strong> <?php echo htmlspecialchars($row['won_date']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You haven't won any auctions yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
