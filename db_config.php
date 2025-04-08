<?php
// Database connection details
// Retrieve database connection details from environment variables
$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');

// Check if the database connection details are set correctly
if (!$host || !$username || !$password || !$database) {
    die("Database connection details are not set correctly.");
}
