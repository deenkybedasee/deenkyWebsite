<?php
@include 'config.php';


if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    header("Location: admin_page.php");
    exit();
}

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Check if user is admin
        if ($user['role'] !== 'admin') {
            $login_error = 'Access denied. You are not an admin.';
        }
        // Verify password
        elseif (!password_verify($password, $user['password'])) {
            $login_error = 'Invalid credentials.';
        } else {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = 'admin';
            $_SESSION['last_login'] = time();

            header("Location: admin_page.php");
            exit();
        }
    } else {
        $login_error = 'Admin not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-form {
            max-width: 400px; margin: 100px auto; padding: 20px;
            background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .login-form h2 { margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;
        }
        .btn { background: #1976d2; color: #fff; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="login-form">
    <h2>Admin Login</h2>
    <?php if (!empty($login_error)): ?>
        <div class="error"><?php echo $login_error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <input type="email" name="email" required placeholder="Admin Email">
        </div>
        <div class="form-group">
            <input type="password" name="password" required placeholder="Password">
        </div>
        <button type="submit" class="btn">Login as Admin</button>
    </form>
</div>

</body>
</html>
