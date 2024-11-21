<?php
session_start(); // Start session to store messages

// Database connection parameters
$host = 'localhost';
$db = 'topbid'; // Database name
$user = 'root'; // XAMPP default username
$pass = ''; // XAMPP default password, typically empty

// Create a new database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture and sanitize form data
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirmPassword = $conn->real_escape_string($_POST['confirm-password']);
    $role = $conn->real_escape_string($_POST['role']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
        die("All fields are required.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if username or email already exists
    $checkUser = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if ($checkUser->num_rows > 0) {
        die("Username or email already taken.");
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', '$role')";
    
    if ($conn->query($sql) === TRUE) {

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $conn->insert_id;;
        // Set success message in session
        $_SESSION['success_message'] = "User '$username' has been created successfully.";
        
        if ($role === 'seller') {
            header("Location: sell-board.php");
        } elseif ($role === 'bidder') {
            header("Location: bid-board.php");
        }
        exit();// Ensure no further code executes after the redirect*/ 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
