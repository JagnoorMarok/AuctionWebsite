<?php
session_start();
include 'db_connection.php'; // Ensure this file exists and the path is correct

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Prepare the query to check user credentials
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['user_id']; // Correctly assign user ID
            
            // Redirect based on role
            if ($user['role'] === 'seller') {
                header("Location: sell-board.php");
            } elseif ($user['role'] === 'bidder') {
                header("Location: bid-board.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to login with error message
    header("Location: login.html?error=" . urlencode($error));
    exit();
} else {
    // If accessed directly, redirect to login page
    header("Location: login.html");
    exit();
}
?>
