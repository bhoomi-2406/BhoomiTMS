<?php
// login.php (This is your main display page now)
session_start();

// Retrieve all potential messages from session
$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$successMessage = $_SESSION['registration_success'] ?? '';

// Determine which form to show, defaulting to 'login'
// This is critical for showing the correct form after a redirect (e.g., after a register error)
$activeForm = $_SESSION['active_form'] ?? 'login';

// IMPORTANT: Clear session variables after reading them, so they don't persist on refresh.
// We are unsetting them individually here, which is safer than session_unset()
unset($_SESSION['login_error']);
unset($_SESSION['register_error']);
unset($_SESSION['registration_success']);
unset($_SESSION['active_form']); // Clear active_form after use

// Helper function to display errors
function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

// Helper function to add 'active' class for CSS
function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login / Register | Technokratos</title>
    <link rel="stylesheet" href="login.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Add these styles to your login.css or keep them here for now */
        .form-box {
            display: none; /* Hide all forms by default */
        }
        .form-box.active {
            display: block; /* Show the active form */
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .success-message {
            color: green;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">

        <?php // Display general success message (e.g., after successful registration) ?>
        <?= !empty($successMessage) ? "<p class='success-message'>$successMessage</p>" : ''; ?>

        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="POST">
                <h2>Login to Technokratos</h2>
                <?= showError($errors['login']); ?>
                <div class="input-group">
                    <label for="email_login">Email Address</label>
                    <input type="email" id="email_login" name="email" placeholder="example@college.com" required />
                </div>
                <div class="input-group">
                    <label for="password_login">Password</label>
                    <input type="password" id="password_login" name="password" placeholder="Your password" required />
                </div>
                <button type="submit" name="login">Login</button>
                <p class="register-link">
                    Don't have an account? <a href="#" onclick="showRegisterForm(); return false;">Register here</a>
                </p>
            </form>
        </div>

        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="POST">
                <h2>Register for Technokratos</h2>
                <?= showError($errors['register']); ?> <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Your full name" required />
                </div>
                <div class="input-group">
                    <label for="email_register">Email Address</label>
                    <input type="email" id="email_register" name="email" placeholder="example@college.com" required />
                </div>
                <div class="input-group">
                    <label for="password_register">Password</label>
                    <input type="password" id="password_register" name="password" required />
                </div>
                <div class="input-group">
                    <select name="role" id="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="register">Register</button>
                <p class="login-link">
                    Already have an account? <a href="#" onclick="showLoginForm(); return false;">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to toggle forms client-side
        function showRegisterForm() {
            document.getElementById('login-form').classList.remove('active');
            document.getElementById('register-form').classList.add('active');
        }

        function showLoginForm() {
            document.getElementById('register-form').classList.remove('active');
            document.getElementById('login-form').classList.add('active');
        }

        // Ensure the correct form is shown on page load based on PHP's $activeForm
        document.addEventListener('DOMContentLoaded', () => {
            const activeFormName = "<?= $activeForm ?>"; // Get value from PHP
            if (activeFormName === 'register') {
                showRegisterForm();
            } else {
                showLoginForm();
            }
        });
    </script>
</body>
</html>