<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "admin_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $input_password = $_POST['password'];

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['password'];

        // Verify the password using password_verify()
        if (password_verify($input_password, $stored_hashed_password)) {
            // Password is correct, start a session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $row['email'];

            // Redirect to the admin dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            header("Location: index.php?error=1");
            exit();
        }
    } else {
        // No user found with that email
        header("Location: index.php?error=1");
        exit();
    }
}

$conn->close();
?>
