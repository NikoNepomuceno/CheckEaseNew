<?php
// Set headers for JSON response and CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Enable error reporting for debugging (remove or disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; // Include database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw POST data
    $postData = json_decode(file_get_contents('php://input'), true);

    // Log received POST data for debugging
    file_put_contents('php://stderr', json_encode($postData) . "\n");

    // Extract className and capacity from POST data
    $className = trim($postData['className'] ?? '');
    $capacity = trim($postData['capacity'] ?? '');

    if (!is_string($className) || !is_numeric($capacity) || $capacity <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid class name or capacity']);
        exit;
    }

    // Generate a unique class code
    $classCode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

    try {
        // Insert class data into the database
        $query = "INSERT INTO classes (class_name, capacity, class_code, created_at) 
                  VALUES (:className, :capacity, :classCode, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':className', $className, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->bindParam(':classCode', $classCode, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'class_code' => $classCode]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to execute query']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn = null;
?>