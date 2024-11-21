<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and the path is correct

// Check if user is logged in and is a bidder
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'bidder') {
    header("Location: login.php");
    exit();
}

// Get listing ID from query parameters
if (!isset($_GET['listing_id'])) {
    header("Location: browse_listings.php");
    exit();
}
$listing_id = intval($_GET['listing_id']);

// Fetch listing details
$sql = "SELECT l.item_name, l.description, l.image_path, l.start_bid, 
               COALESCE(MAX(b.bid_amount), l.start_bid) AS current_bid 
        FROM listings l 
        LEFT JOIN bids b ON l.listing_id = b.listing_id 
        WHERE l.listing_id = ? AND l.status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: browse_listings.php");
    exit();
}
$listing = $result->fetch_assoc();

// Handle bid submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $bid_amount = floatval($_POST['bid_amount']);
    $current_bid = floatval($listing['current_bid']);

    if ($bid_amount > $current_bid) {
        // Insert new bid
        $bid_sql = "INSERT INTO bids (listing_id, user_id, bid_amount) VALUES (?, ?, ?)";
        $bid_stmt = $conn->prepare($bid_sql);
        $bid_stmt->bind_param("iid", $listing_id, $user_id, $bid_amount);

        if ($bid_stmt->execute()) {
            $message = "Bid placed successfully!";
        } else {
            $message = "Error placing bid. Please try again.";
        }
    } else {
        $message = "Bid must be greater than the current bid.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing Details</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Display toast notification
        function showToast(message) {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.classList.add("show");
            setTimeout(() => {
                toast.classList.remove("show");
            }, 5000);
        }
    </script>
</head>
<body onload="showToast('<?php echo htmlspecialchars($message); ?>')">
    <header class="navbar">
        <a href="browse_listings.php" class="logo">TopBid</a>
        <div class="nav-buttons">
            <a href="bid-board.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="listing-details">
        <h1><?php echo htmlspecialchars($listing['item_name']); ?></h1>
        <img src="<?php echo htmlspecialchars($listing['image_path']); ?>" alt="<?php echo htmlspecialchars($listing['item_name']); ?>">
        <p><?php echo htmlspecialchars($listing['description']); ?></p>
        <p>Starting Bid: ₹<?php echo htmlspecialchars($listing['start_bid']); ?></p>
        <p>Current Bid: ₹<?php echo htmlspecialchars($listing['current_bid']); ?></p>

        <form action="listing_details.php?listing_id=<?php echo $listing_id; ?>" method="POST">
            <label for="bid_amount">Your Bid (₹):</label>
            <input type="number" id="bid_amount" name="bid_amount" step="0.01" min="<?php echo htmlspecialchars($listing['current_bid'] + 1); ?>" required>
            <button type="submit">Place Bid</button>
        </form>
    </div>

    <!-- Toast Notification -->
    <div id="toast"></div>
</body>
</html>
