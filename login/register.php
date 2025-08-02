<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active':'';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register | Technokratos</title>
  <link rel="stylesheet" href="register.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="register-container">
    <div class = "form-box <?= isActiveForm('register', $activeForm); ?>" id = "register-form">
    <form action="login_register.php" method="POST">
        <h2>Register for Technokratos</h2>
        <?= showError($errors['login']); ?>
      <div class="input-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Your full name" required />
      </div>

      <div class="input-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="example@college.com" required />
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="passsword" name="password"  required />
      </div>

      <div class="input-group">
        <select name="role" id="role">
  <option value="user">User</option>
  <option value="admin">Admin</option>
</select>
      </div>

      <button type="submit" name="register">Register</button>
    </form>
    </div>
  </div>
</body>
</html>