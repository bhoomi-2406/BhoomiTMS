<?php
// login_register.php
session_start();
require_once 'config.php'; // Include your database connection

// --- Registration Logic ---
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // 1. Check if email exists using prepared statement (Good!)
    $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email exists: Set error message and redirect to login.php (which now acts as index)
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register'; // Keep the register form active
        $stmt->close();
        header("Location: login.php"); // Redirect back to login.php
        exit();
    }
    $stmt->close(); // Close the statement after the check

    // 2. Insert new user if email does not exist
    $stmt = $conn->prepare("INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    if ($stmt->execute()) {
        $stmt->close();
        // Registration successful: Set success message and redirect to login.php (show login form)
        $_SESSION['registration_success'] = 'Registration successful! Please login.';
        $_SESSION['active_form'] = 'login'; // After success, default to login form
        header("Location: login.php"); // Redirect back to login.php
        exit();
    } else {
        // Error during insert: Set error message and redirect to login.php (show register form)
        $_SESSION['register_error'] = 'Registration failed. Please try again.';
        $_SESSION['active_form'] = 'register'; // Keep the register form active
        $stmt->close();
        header("Location: login.php"); // Redirect back to login.php
        exit();
    }
}

// --- Login Logic (IMPORTANT: Fix SQL Injection Vulnerability here!) ---
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement for login (Fixing SQL Injection!)
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful: Set session variables and redirect to user/admin page
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $stmt->close(); // Close statement on success

            if ($user['role'] === 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        }
    }
    // Login failed (no user found or wrong password): Set error message and redirect
    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login'; // Keep the login form active
    if (isset($stmt)) { // Ensure statement is closed even on failure
        $stmt->close();
    }
    header("Location: login.php"); // Redirect back to login.php
    exit();
}

// If someone tries to access login_register.php directly without POST data
// Redirect them to the main login page
header("Location: login.php");
exit();
?>