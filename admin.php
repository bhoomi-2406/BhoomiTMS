<?php
// admin_dashboard.php
session_start();
require_once 'config.php'; // Make sure this path is correct

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// --- PHP to Fetch Data from Database ---

// Fetch Total Users
$result_users = $conn->query("SELECT COUNT(*) AS total_users FROM user");
$total_users = $result_users->fetch_assoc()['total_users'] ?? 0;

// Fetch Upcoming Events (assuming 'events' table with 'event_date')
$current_date = date('Y-m-d H:i:s');
$result_events = $conn->query("SELECT COUNT(*) AS upcoming_events FROM events WHERE event_date > '$current_date'");
$upcoming_events = $result_events->fetch_assoc()['upcoming_events'] ?? 0;

// Fetch New Registrations (e.g., from the last 24 hours)
$last_24_hours = date('Y-m-d H:i:s', strtotime('-24 hours'));
$result_new_users = $conn->query("SELECT COUNT(*) AS new_registrations FROM user WHERE created_at > '$last_24_hours'"); // Assumes a 'created_at' column
$new_registrations = $result_new_users->fetch_assoc()['new_registrations'] ?? 0;

$conn->close(); // Close the database connection after fetching all data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Technokratos</title>
    <!-- Use a modern font like Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Link to the dedicated admin CSS file -->
    <link rel="stylesheet" href="admin_style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Technokratos</h3>
            <span class="role-badge">Admin</span>
        </div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="#"><i class="fas fa-users"></i>User Management</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i>Event Management</a></li>
            <li><a href="#"><i class="fas fa-question-circle"></i>Queries & Feedback</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Log Out</a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <header class="main-header">
            <h1 class="main-title">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <p class="main-subtitle">Here is an overview of your platform.</p>
        </header>

        <!-- Dashboard Cards -->
        <div class="card-grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <div class="card-content">
                    <span class="card-title">Total Users</span>
                    <span class="card-value"><?php echo $total_users; ?></span>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="card-content">
                    <span class="card-title">Upcoming Events</span>
                    <span class="card-value"><?php echo $upcoming_events; ?></span>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                <div class="card-content">
                    <span class="card-title">New Registrations</span>
                    <span class="card-value"><?php echo $new_registrations; ?></span>
                </div>
            </div>
        </div>

        <!-- Placeholder for a table or chart -->
        <section class="recent-activity">
            <h2>Recent Activity</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Details</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>User Registered</td>
                            <td>John Doe</td>
                            <td>john.doe@example.com</td>
                            <td>2024-05-18 10:30</td>
                        </tr>
                        <tr>
                            <td>Event Created</td>
                            <td>Jane Smith</td>
                            <td>TechFest 2024</td>
                            <td>2024-05-18 09:45</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
