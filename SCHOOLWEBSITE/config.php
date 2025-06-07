<?php

// Add this at the VERY TOP (before any other code)
ob_start();
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$username = 'deenky';
$password = 'deenky123@esha';
$database = 'users_db';

// Create connection
$conn = mysqli_connect('localhost', 'deenky', 'deenky123@esha', 'users_db');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for proper Unicode support
mysqli_set_charset($conn, "utf8mb4");

// Function to check if tables exist
function checkTables($conn) {
    $tables = ['user', 'products', 'orders', 'order_items'];
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) == 0) {
            return false;
        }
    }
    return true;
}


// Check if tables exist, if not create them
if (!checkTables($conn)) {
    $sql = file_get_contents('database.sql');
    if (mysqli_multi_query($conn, $sql)) {
        do {
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
    }
}

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Base URL configuration
$base_url = 'http://localhost:8000'; // Change this according to your setup

// Common functions
function redirect($path) {
    global $base_url;
    header("Location: $base_url/$path");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_name']) || isset($_SESSION['admin_name']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
?>