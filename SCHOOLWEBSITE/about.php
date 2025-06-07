<?php
session_start();
@include 'config.php';

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Back to School Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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
        
        /* Header */
        header {
            background-color: #fdd835;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .user-info a {
            color: #1976d2;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid #1976d2;
            border-radius: 5px;
            margin-left: 10px;
        }
        .user-info a:hover {
            background: #1976d2;
            color: white;
        }
        /* Navigation */
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
        
        /* Main Content */
        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* About Page Specific */
        .about-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    .about-title {
        color: #1976d2;
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 2.2rem;
    }
    
    .about-intro {
        text-align: center;
        font-size: 1.1rem;
        color: #555;
        max-width: 800px;
        margin: 0 auto 2rem;
        line-height: 1.7;
    }
    
    .about-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .about-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }
    
    .about-card:hover {
        transform: translateY(-5px);
    }
    
    .section-title {
        color: #1976d2;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #fdd835;
    }
    
    .feature-list, .product-list {
        list-style: none;
        margin-top: 1rem;
    }
    
    .feature-list li, .product-list li {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    
    .feature-list i {
        color: #4CAF50;
    }
    
    .product-list i {
        color: #1976d2;
    }
    
    .delivery-tier {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1rem 0;
        padding-bottom: 1rem;
        border-bottom: 1px dashed #eee;
    }
    
    .delivery-badge {
        background: #f5f5f5;
        color: #333;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-weight: bold;
        min-width: 60px;
        text-align: center;
    }
    
    .delivery-badge.free {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .delivery-note {
        margin-top: 1rem;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
    }
    
    address {
        font-style: normal;
        line-height: 1.7;
    }
    
    .hours {
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
    }
        
        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
        
        /* Shipping Banner */
        .shipping-banner {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <!-- Shipping Banner -->
    <div class="shipping-banner">
        <p>Free delivery in Mauritius for orders over Rs1000</p>
    </div>

    <!-- Header -->
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

    <!-- Navigation -->
    <nav class="secondary-nav">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php" class="active"><i class="fas fa-info-circle"></i> About</a>
        <a href="best_sellers.php"><i class="fas fa-star"></i> Best Sellers</a>
        <a href="help.php"><i class="fas fa-question-circle"></i> Help</a>
        <a href="cart.php" class="cart-icon">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count"><?php echo $cart_count; ?></span>
        </a>
    </nav>

    <!-- Main Content -->
   <main class="main-content">
    <div class="about-container">
        <section class="about-section">
            <h1 class="about-title">Welcome to Back to School Store</h1>
            <p class="about-intro">Your one-stop shop for quality school supplies since 2020. We're committed to providing affordable, durable products that meet students' needs.</p>
            
            <div class="about-grid">
                <section class="about-card">
                    <h2 class="section-title"><i class="fas fa-bullseye"></i> Our Mission</h2>
                    <p>To enhance education by offering:</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Premium quality supplies</li>
                        <li><i class="fas fa-check-circle"></i> Competitive local pricing</li>
                        <li><i class="fas fa-check-circle"></i> Reliable island-wide delivery</li>
                    </ul>
                </section>

                <section class="about-card">
                    <h2 class="section-title"><i class="fas fa-box-open"></i> Our Products</h2>
                    <ul class="product-list">
                        <li><i class="fas fa-pencil-alt"></i> Notebooks & stationery sets</li>
                        <li><i class="fas fa-briefcase"></i> School bags & lunch boxes</li>
                        <li><i class="fas fa-paint-brush"></i> Art supplies & craft materials</li>
                        <li><i class="fas fa-calculator"></i> Scientific calculators</li>
                    </ul>
                </section>

                <section class="about-card delivery-info">
                    <h2 class="section-title"><i class="fas fa-truck"></i> Delivery Policy</h2>
                    <div class="delivery-tier">
                        <span class="delivery-badge free">FREE</span>
                        <p>Orders over <strong>Rs1000</strong></p>
                    </div>
                    <div class="delivery-tier">
                        <span class="delivery-badge">Rs150</span>
                        <p>Orders under Rs1000</p>
                    </div>
                    <p class="delivery-note"><i class="fas fa-clock"></i> Same-day delivery in urban areas</p>
                </section>

                <section class="about-card location">
                    <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Visit Us</h2>
                    <address>
                        <p><strong>Rose Hill Main Store</strong></p>
                        <p>123 Education Street</p>
                        <p>Rose Hill, Mauritius</p>
                        <p><i class="fas fa-phone"></i> +230 123 4567</p>
                    </address>
                    <p class="hours"><i class="fas fa-clock"></i> Open: Mon-Sat, 9AM-5PM</p>
                </section>
            </div>
        </section>
    </div>
</main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Back to School Store. All rights reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>