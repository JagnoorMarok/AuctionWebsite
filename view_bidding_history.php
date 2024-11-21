<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'seller' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Fetch all listings for the seller with bid details
$sql = "
    SELECT l.listing_id, l.item_name, l.image_path, l.status, b.bid_id, b.user_id AS bidder_id, b.bid_amount, b.bid_time
    FROM listings l
    LEFT JOIN bids b ON l.listing_id = b.listing_id
    WHERE l.seller_id = ? 
    ORDER BY b.bid_amount DESC
";

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
    <title>View Bidding History</title>
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

    <div class="bidding-history-container">
        <h1>Bids on Your Products</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="bidding-grid">
                <?php 
                $current_listing_id = null; 
                while ($row = $result->fetch_assoc()): 
                ?>
                    <?php if ($current_listing_id !== $row['listing_id']): ?>
                        <?php 
                        if ($current_listing_id !== null): 
                            echo '</div><form method="POST" action="mark_inactive.php">
                                    <input type="hidden" name="listing_id" value="' . htmlspecialchars($current_listing_id) . '">
                                    <button type="submit">Mark as Sold</button>
                                  </form></div>';
                        endif; 
                        $current_listing_id = $row['listing_id']; 
                        ?>
                        <div class="bidding-card">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>">
                            <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                            <p>Status: <?php echo htmlspecialchars(ucfirst($row['status'])); ?></p>
                            <div class="bids-list">
                                <h4>Bids:</h4>
                    <?php endif; ?>

                    <?php if ($row['bid_id'] !== null): ?>
                        <p>
                            Bidder ID: <?php echo htmlspecialchars($row['bidder_id']); ?> | 
                            Amount: ₹<?php echo htmlspecialchars($row['bid_amount']); ?> | 
                            Time: <?php echo htmlspecialchars($row['bid_time']); ?>
                        </p>
                    <?php else: ?>
                        <p>No bids yet.</p>
                    <?php endif; ?>

                <?php endwhile; ?>
                <?php 
                if ($current_listing_id !== null): 
                    echo '</div><form method="POST" action="mark_inactive.php">
                            <input type="hidden" name="listing_id" value="' . htmlspecialchars($current_listing_id) . '">
                            <button type="submit">Mark as Sold</button>
                          </form></div>';
                endif; 
                ?>
            </div>
        <?php else: ?>
            <p>No products or bids found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
