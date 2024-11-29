<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';  
use \Firebase\JWT\JWT;

$secretKey = 'ayawqna';  

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (empty($authHeader)) {
        echo json_encode(['success' => false, 'error' => 'Authorization token missing or malformed']);
        exit;
    }

    list($jwt) = sscanf($authHeader, 'Bearer %s');
    if (empty($jwt)) {
        echo json_encode(['success' => false, 'error' => 'Authorization token missing']);
        exit;
    }

    try {
        $decoded = JWT::decode($jwt, $secretKey, ['HS256']);
        $userId = $decoded->user_id; 
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Invalid or expired token: ' . $e->getMessage()]);
        exit;
    }

    $postData = json_decode(file_get_contents('php://input'), true);
    if (empty($postData)) {
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
        exit;
    }

    $className = trim($postData['class_name'] ?? '');
    $capacity = trim($postData['capacity'] ?? '');

    if (empty($className) || !is_numeric($capacity) || $capacity <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid class name or capacity']);
        exit;
    }

    $classCode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
    try {
        $query = "INSERT INTO classes (class_name, capacity, class_code, created_by, created_at) 
                  VALUES (:class_name, :capacity, :class_code, :created_by, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':class_name', $className, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->bindParam(':class_code', $classCode, PDO::PARAM_STR);
        $stmt->bindParam(':created_by', $userId, PDO::PARAM_INT);

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
