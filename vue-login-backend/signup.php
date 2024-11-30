<?php
require __DIR__ . '/vendor/autoload.php'; 
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

use \Firebase\JWT\JWT;
require 'vendor/autoload.php';
include 'db.php';

$secret_key = getenv('JWT_SECRET_KEY') ?: "your_default_secret_key"; 

$data = json_decode(file_get_contents("php://input"));
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

if (empty($data->firstname) || empty($data->lastname) || empty($data->email) || empty($data->password) || empty($data->role)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

$firstname = htmlspecialchars($data->firstname);
$lastname = htmlspecialchars($data->lastname);
$email = htmlspecialchars($data->email);
$password = password_hash($data->password, PASSWORD_DEFAULT);
$role = htmlspecialchars($data->role);

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }
    $token = bin2hex(random_bytes(32));

    $sql = "INSERT INTO users (firstname, lastname, email, password, role, token) VALUES (:firstname, :lastname, :email, :password, :role, :token)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $password,
        'role' => $role,
        'token' => $token 
    ]);

    if ($stmt->rowCount() > 0) {

        $payload = [
            "iss" => "http://localhost/CheckEaseExp-NEW/vue-login-backend/signup.php",  
            "aud" => "http://localhost/CheckEaseExp-NEW/vue-login-backend/signup.php", 
            "iat" => time(),
            "exp" => time() + (60*60),  
            "data" => [
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "role" => $role
            ]
        ];
        try {
            $jwt = JWT::encode($payload, $secret_key, 'HS256');
        } catch (Exception $e) {
            error_log("JWT Encoding Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error generating token']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $jwt,
            'firstname' => $firstname
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again later.']);
}
?>
