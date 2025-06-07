<?php


@include 'config.php';



// Check if user is logged in
if(!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
    exit();
}

// Get user information
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'] ?? '';

// Get user's orders
$user_id = $_SESSION['user_id'] ?? '';
$orders_query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
$orders_result = mysqli_query($conn, $orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Dashboard - Back to School Store</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
        .user-dashboard {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .user-profile {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .user-profile h2 {
            color: #1976d2;
            margin-bottom: 1rem;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .profile-item {
            padding: 1rem;
            background: #f5f5f5;
            border-radius: 5px;
        }

        .profile-item label {
            display: block;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .profile-item span {
            color: #333;
            font-weight: 500;
        }

        .orders-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .orders-section h2 {
            color: #1976d2;
            margin-bottom: 1.5rem;
        }

        .orders-list {
            display: grid;
            gap: 1rem;
        }

        .order-card {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 5px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            align-items: center;
        }

        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            text-align: center;
        }

        .status-pending {
            background: #fff3e0;
            color: #e65100;
        }

        .status-processing {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelled {
            background: #ffebee;
            color: #c62828;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>User Dashboard</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                <a href="index.php">Back to Store</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </header>

    <div class="user-dashboard">
        <div class="user-profile">
            <h2>Profile Information</h2>
            <div class="profile-info">
                <div class="profile-item">
                    <label>Name</label>
                    <span><?php echo htmlspecialchars($user_name); ?></span>
                </div>
                <div class="profile-item">
                    <label>Email</label>
                    <span><?php echo htmlspecialchars($user_email); ?></span>
                </div>
                <div class="profile-item">
                    <label>Member Since</label>
                    <span><?php echo date('F Y', strtotime($_SESSION['created_at'] ?? 'now')); ?></span>
                </div>
            </div>
        </div>

        <div class="orders-section">
            <h2>My Orders</h2>
            <div class="orders-list">
                <?php if(mysqli_num_rows($orders_result) > 0): ?>
                    <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                        <div class="order-card">
                            <div>
                                <label>Order ID</label>
                                <span>#<?php echo $order['id']; ?></span>
                            </div>
                            <div>
                                <label>Date</label>
                                <span><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div>
                                <label>Total</label>
                                <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div>
                                <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>