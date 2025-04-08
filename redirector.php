<?php
include 'db_config.php'; // Include the database configuration file
// Sanitize the requested URI to prevent security vulnerabilities
$path = filter_var(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), FILTER_SANITIZE_URL);
$slug = basename($path, ".php");

// Retrieve the base path from an environment variable or default to '/homepage/'
$base_path = getenv('BASE_PATH') ?: '/homepage/';

// Handle non-PHP URLs gracefully
if (pathinfo($path, PATHINFO_EXTENSION) !== 'php' && strpos($path, $base_path) !== 0) {
    header("Location: {$base_path}error", true, 301);
    exit;
}

// Check if the requested URI is already under the base path to prevent a redirect loop
if (strpos($path, $base_path) === 0) {
    exit;
}

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Query for an exact match of the slug
$sql = "SELECT ID FROM wp_posts WHERE post_name = ? AND post_status = 'publish' AND post_type IN ('post', 'page') LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $post_id = $row['ID'];

    // Query for the permalink structure
    $permalink_sql = "SELECT option_value FROM wp_options WHERE option_name = 'siteurl' LIMIT 1";
    $permalink_result = $conn->query($permalink_sql);

    if ($permalink_result->num_rows > 0) {
        $site_url = $permalink_result->fetch_assoc()['option_value'];
        $permalink = $site_url . '/?p=' . $post_id;

        // Redirect to the found page or post
        header("Location: $permalink", true, 301);
        exit;
    }
}

// Query for similar posts or pages
$sql = "SELECT ID FROM wp_posts WHERE post_title LIKE ? AND post_status = 'publish' AND post_type IN ('post', 'page') LIMIT 1";
$stmt = $conn->prepare($sql);
$search_term = '%' . $slug . '%';
$stmt->bind_param('s', $search_term);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $post_id = $row['ID'];

    // Query for the permalink structure
    $permalink_sql = "SELECT option_value FROM wp_options WHERE option_name = 'siteurl' LIMIT 1";
    $permalink_result = $conn->query($permalink_sql);

    if ($permalink_result->num_rows > 0) {
        $site_url = $permalink_result->fetch_assoc()['option_value'];
        $permalink = $site_url . '/?p=' . $post_id;

        // Redirect to the similar post or page
        header("Location: $permalink", true, 301);
        exit;
    }
}

// Close the database connection
$conn->close();
