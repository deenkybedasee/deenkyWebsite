<?php
// Setup script to organize files and check permissions

// Define directories to create
$directories = [
    'css',
    'js',
    'images',
    'includes',
    'uploads'
];

// Create directories if they don't exist
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: $dir\n";
    }
}

// Check file permissions
$files = [
    'index.php',
    'login_form.php',
    'admin_page.php',
    'user_page.php',
    'config.php',
    'logout.php',
    'database.sql',
    '.htaccess',
    'css/style.css'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        if (($perms & 0644) !== 0644) {
            chmod($file, 0644);
            echo "Fixed permissions for: $file\n";
        }
    } else {
        echo "Warning: File not found: $file\n";
    }
}

// Check database connection
require_once 'config.php';
if ($conn) {
    echo "Database connection successful\n";
    
    // Check if tables exist
    if (checkTables($conn)) {
        echo "All required tables exist\n";
    } else {
        echo "Creating required tables...\n";
        $sql = file_get_contents('database.sql');
        if (mysqli_multi_query($conn, $sql)) {
            echo "Tables created successfully\n";
        } else {
            echo "Error creating tables: " . mysqli_error($conn) . "\n";
        }
    }
} else {
    echo "Database connection failed\n";
}

echo "\nSetup complete! Your application should now be accessible at:\n";
echo "http://localhost:8000/\n";
?>