<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and has the 'seller' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $start_bid = $conn->real_escape_string($_POST['start_bid']);
    $seller_id = $_SESSION['user_id']; 

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $image_path = $conn->real_escape_string($target_file);

    // Ensure the uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert listing into database
        $sql = "INSERT INTO listings (seller_id, item_name, description, start_bid, image_path)
                VALUES ('$seller_id', '$item_name', '$description', '$start_bid', '$image_path')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['toast_message'] = "Listing added successfully!";
        } else {
            $_SESSION['toast_message'] = "Error: " . $conn->error;
        }
    } else {
        $_SESSION['toast_message'] = "Error uploading image.";
    }

    // Redirect to the same page to display the toast message
    header("Location: add_listing.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Listing</title>
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

    <div class="form-container">
        <h2>Add New Listing</h2>
        <form action="add_listing.php" method="POST" enctype="multipart/form-data">
            <label for="item_name">Item Name</label>
            <input type="text" id="item_name" name="item_name" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="start_bid">Start Bid</label>
            <input type="number" step="1" id="start_bid" name="start_bid" required>

            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Add Listing</button>
        </form>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"><?php echo isset($_SESSION['toast_message']) ? $_SESSION['toast_message'] : ''; ?></div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toast = document.getElementById('toast');
            if (toast.textContent.trim() !== "") {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 5000); // Remove the toast after 5 seconds
            }
        });
    </script>
</body>
</html>
