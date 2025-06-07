<?php
session_start();
@include 'config.php';

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $problem = mysqli_real_escape_string($conn, $_POST['problem']);

    $insert_query = "INSERT INTO customer_feedback (name, email, problem) VALUES ('$name', '$email', '$problem')";
    mysqli_query($conn, $insert_query);
    $success_msg = "Thank you! Your feedback has been submitted.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Back to School Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .help-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .help-form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background: #1976d2;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #1565c0;
        }
        
        .success-message {
            background: #4CAF50;
            color: white;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header (Same as index.php) -->
    <header>
        <div class="header-content">
            <h1>Back to School Store</h1>
            <div class="user-info">
                <?php if(isset($_SESSION['user_name'])): ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="user_page.php">My Account</a>
                    <a href="logout.php">Logout</a>
                <?php elseif(isset($_SESSION['admin_name'])): ?>
                    <span>Welcome, Admin <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="admin_page.php">Admin Panel</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login_form.php">Login</a>
                    <a href="login_form.php?register=1">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Navigation (Same as index.php) -->
    <nav class="secondary-nav">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="best_sellers.php"><i class="fas fa-star"></i> Best Sellers</a>
        <a href="help.php" class="active"><i class="fas fa-question-circle"></i> Help</a>
        <a href="cart.php" class="cart-icon">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count"><?php echo $cart_count; ?></span>
        </a>
    </nav>

    <!-- Help Content -->
    <main class="main-content">
        <div class="help-container">
            <h1>Help Center</h1>
            <p>Have questions or need assistance? Contact us using the form below.</p>
            
            <?php if(isset($success_msg)): ?>
                <div class="success-message">
                    <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>
            
            <div class="help-form">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="problem">How can we help you?</label>
                        <textarea id="problem" name="problem" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer (Same as index.php) -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Back to School Store. All rights reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>