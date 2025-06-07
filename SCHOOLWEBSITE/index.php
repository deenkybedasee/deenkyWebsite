<?php
session_start();
@include 'config.php';

// Redirect to login if trying to access protected pages while not logged in
$protected_pages = ['cart.php', 'checkout.php'];
$current_page = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['user_name']) && !isset($_SESSION['admin_name']) && in_array($current_page, $protected_pages)) {
    header('location: login_form.php');
    exit();
}

// Get cart count
$cart_count = 0;
if(isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back to School E-commerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Add new styles for the shipping banner and navigation */
        .shipping-banner {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .shipping-banner p {
            margin: 5px 0;
        }
        
        .promo-code {
            font-weight: bold;
            color: #d32f2f;
        }
        
        .secondary-nav {
            display: flex;
            justify-content: center;
            background-color: #1976d2;
            padding: 10px 0;
        }
        
        .secondary-nav a {
            color: white;
            padding: 0 15px;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .secondary-nav a i {
            margin-right: 5px;
        }
        
        .secondary-nav a:hover {
            color: #fdd835;
        }
        
        .cart-icon {
            position: relative;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #fdd835;
            color: #333;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
       
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Header & Navigation */
        header {
            background-color: #fdd835;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }
        
        header img {
            max-width: 180px;
        }
        
        nav {
            background-color: #1976d2;
            padding: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .nav-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 15px 25px;
            display: inline-block;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        
        nav a:hover {
            background-color: #1565c0;
        }
        
        /* User Info Styles */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info span {
            color: #333;
            font-weight: 500;
        }
        
        .user-info a {
            color: #1976d2;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid #1976d2;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .user-info a:hover {
            background: #1976d2;
            color: white;
        }
        
        /* Search Bar */
        .search-container {
            padding: 20px 0;
            background-color: #f5f5f5;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .search-bar {
            display: flex;
            justify-content: center;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-bar form {
            display: flex;
            width: 100%;
        }
        
        .search-bar input[type="text"] {
            padding: 12px 15px;
            width: 100%;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 4px 0 0 4px;
            font-size: 16px;
            outline: none;
        }
        
        .search-bar button {
            padding: 12px 20px;
            background-color: #1976d2;
            color: #fff;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }
        
        .search-bar button:hover {
            background-color: #1565c0;
        }
        
        /* Banner */
        .banner {
            background: linear-gradient(to right, #e3f2fd, #bbdefb);
            padding: 30px 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .banner h2 {
            color: #0d47a1;
            font-size: 24px;
        }
        
        /* Section Headers */
        main h2 {
            text-align: center;
            margin: 40px 0 20px;
            color: #1976d2;
            position: relative;
            padding-bottom: 10px;
        }
        
        main h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #fdd835;
        }
        
        /* Categories */
        .categories {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .category {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            width: 220px;
            overflow: hidden;
        }
        
        .category:hover {
            transform: translateY(-5px);
        }
        
        .category img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .category h3 {
            margin: 15px 0 5px;
            color: #333;
            padding: 0 10px;
        }
        
        .category p {
            color: #1976d2;
            font-weight: bold;
            margin-bottom: 15px;
            cursor: pointer;
            padding: 0 10px;
        }
        
        .category p:hover {
            text-decoration: underline;
        }
        
        /* Products */
        .product-section {
            background-color: white;
            padding: 30px 20px;
            margin: 30px 0;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .section-title {
            font-size: 22px;
            margin-bottom: 25px;
            color: #333;
            border-left: 4px solid #fdd835;
            padding-left: 15px;
        }
        
        .products {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
        }
        
        .product {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            width: 220px;
            overflow: hidden;
            padding-bottom: 15px;
        }
        
        .product:hover {
            transform: translateY(-5px);
        }
        
        .product img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .product h3 {
            margin: 15px 0 5px;
            padding: 0 10px;
        }
        
        .product p {
            color: #d32f2f;
            font-weight: bold;
            margin: 5px 0 15px;
            padding: 0 10px;
        }
        
        .product button {
            padding: 8px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        
        .product button:hover {
            background-color: #218838;
        }
        
        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
        
        footer p {
            margin: 0;
        }
        
        /* Responsive Design */
        @media screen and (max-width: 768px) {
            nav a {
                padding: 12px 15px;
                font-size: 14px;
            }
            
            .user-info {
                position: static;
                text-align: center;
                margin-top: 10px;
            }
            
            .category, .product {
                width: 200px;
            }
        }
        
        @media screen and (max-width: 480px) {
            .categories, .products {
                gap: 15px;
            }
            
            .category, .product {
                width: 100%;
                max-width: 280px;
            }
            
            .banner h2 {
                font-size: 20px;
            }
            
            .user-info {
                font-size: 14px;
            }
        }
        
        /* Additional styles for the main page */
        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .welcome-section h1 {
            color: #1976d2;
            margin-bottom: 1rem;
        }
        
        .welcome-section p {
            color: #666;
            font-size: 1.1rem;
        }
         
    </style>
</head>
<body>

    <div class="shipping-banner">
        <p>Free Delivery over RS1000</p>
        <p>Free delivery for orders over ₨1000 | Use code: <span class="promo-code">MAURIFREE</span></p>
    </div>

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

    <!-- Secondary Navigation -->
    <nav class="secondary-nav">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="best_sellers.php"><i class="fas fa-star"></i> Best Sellers</a>
        <a href="help.php"><i class="fas fa-question-circle"></i> Help</a>
        <a href="cart.php" class="cart-icon">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count"><?php echo $cart_count; ?></span>
        </a>
    </nav>

    <!-- Search Bar -->
    <div class="search-container">
        <div class="search-bar">
            <form action="search.php" method="get">
                <input type="text" name="q" placeholder="Search for products...">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
    </div>

    <main class="main-content">
        <div class="welcome-section">
            <h1>Welcome to Back to School Store</h1>
            <p>Your one-stop shop for all your school supplies!</p>
        </div>
        
        <div class="categories">
            <div class="category">
                <h3>Stationery</h3>
                <p>Pens, pencils, notebooks, and more</p>
            </div>
            <div class="category">
                <h3>Backpacks</h3>
                <p>Durable and stylish school bags</p>
            </div>
            <div class="category">
                <h3>Art Supplies</h3>
                <p>Colors, brushes, and craft materials</p>
            </div>
            <div class="category">
                <h3>Electronics</h3>
                <p>Calculators, laptops, and accessories</p>
            </div>
        </div>
    </main>

    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>