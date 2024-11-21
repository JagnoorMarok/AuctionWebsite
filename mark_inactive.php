<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has the 'seller' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listing_id'])) {
    $listing_id = intval($_POST['listing_id']);
    $seller_id = $_SESSION['user_id'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Fetch the highest bidder for the listing
        $sql = "
            SELECT user_id AS bidder_id, bid_amount 
            FROM bids 
            WHERE listing_id = ? 
            ORDER BY bid_amount DESC 
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listing_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $highest_bid = $result->fetch_assoc();

        if ($highest_bid) {
            $bidder_id = $highest_bid['bidder_id'];
            $final_bid_amount = $highest_bid['bid_amount'];

            // Insert the record into the won_auctions table
            $insert_sql = "
                INSERT INTO won_auctions (listing_id, seller_id, bidder_id, final_bid_amount)
                VALUES (?, ?, ?, ?)
            ";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iiid", $listing_id, $seller_id, $bidder_id, $final_bid_amount);
            $insert_stmt->execute();
        } else {
            throw new Exception("No bids found for the listing.");
        }

        // Mark the listing as inactive
        $update_sql = "
            UPDATE listings 
            SET status = 'Closed' 
            WHERE listing_id = ? AND seller_id = ?
        ";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $listing_id, $seller_id);
        $update_stmt->execute();

        // Commit the transaction
        $conn->commit();

        $_SESSION['toast_message'] = "Listing marked as sold and added to won auctions.";
    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        $conn->rollback();
        $_SESSION['toast_message'] = "Error: " . $e->getMessage();
    }
} else {
    $_SESSION['toast_message'] = "Invalid request.";
}

// Redirect back to the bidding history page
header("Location: view_bidding_history.php");
exit();
?>
