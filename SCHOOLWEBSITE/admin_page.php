<?php
session_start();
@include 'config.php';

// Enhanced security check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login_form.php'); // Redirect to login page if not an admin
    exit();
}

// Secure database fetch with prepared statements
$feedback_query = "SELECT * FROM customer_feedback ORDER BY created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_query);
if (!$feedback_result) die("Database error: ".mysqli_error($conn));

// Handle status updates with security checks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $feedback_id = (int)$_POST['feedback_id'];
    $new_status = in_array($_POST['new_status'], ['pending', 'resolved']) ? $_POST['new_status'] : 'pending';

    $update_query = "UPDATE customer_feedback SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $feedback_id);
    mysqli_stmt_execute($stmt);

    header('Location: admin_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Back to School Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .security-badge {
            background: #4CAF50;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            margin-left: 10px;
            vertical-align: middle;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .access-denied {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #d32f2f;
        }
        .admin-title {
            color: #1976d2;
            font-size: 1.8rem;
        }
        
        .admin-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .admin-card h3 {
            color: #1976d2;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .feedback-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .feedback-table th, 
        .feedback-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .feedback-table th {
            background-color: #f5f5f5;
            font-weight: 500;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: inline-block;
        }
        
        .status-pending {
            background: #fff3e0;
            color: #e65100;
        }
        
        .status-resolved {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-form {
            display: flex;
            gap: 0.5rem;
        }
        
        .status-select {
            padding: 4px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .update-btn {
            background: #1976d2;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        
        .no-feedback {
            color: #666;
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>
<body>

<header>
    <div class="header-content">
        <h1>Admin Panel<span class="security-badge">Secure</span></h1>
        <div class="user-info">
            <span>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="index.php">View Store</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<div class="admin-container">
    <div class="admin-header">
        <h2 class="admin-title">Admin Dashboard</h2>
        <small>Last login: <?php echo date('M d, Y H:i', $_SESSION['last_login'] ?? time()); ?></small>
    </div>

    <!-- Customer Feedback Section -->
    <div class="admin-card">
        <h3><i class="fas fa-comments"></i> Customer Feedback</h3>
        
        <?php if (mysqli_num_rows($feedback_result) > 0): ?>
            <div class="table-responsive">
                <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($feedback = mysqli_fetch_assoc($feedback_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['problem']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($feedback['created_at'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $feedback['status']; ?>">
                                <?php echo ucfirst($feedback['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form class="status-form" method="POST">
                                <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                                <select name="new_status" class="status-select">
                                    <option value="pending" <?php echo $feedback['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="resolved" <?php echo $feedback['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                                <button type="submit" name="update_status" class="update-btn">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-feedback">No feedback submissions yet.</p>
        <?php endif; ?>
    </div>

    <div class="admin-grid">
      
    </div>

</div>

</body>
</html>

