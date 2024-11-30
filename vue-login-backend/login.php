<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

use \Firebase\JWT\JWT;

require 'vendor/autoload.php';  
include 'db.php';

$secret_key = getenv('JWT_SECRET_KEY') ?: "your_default_secret_key"; 

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(['success' => false, 'message' => 'Missing email or password.']);
    exit;
}

$email = htmlspecialchars($data->email);
$password = htmlspecialchars($data->password);

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $payload = [
            "iss" => "http://localhost/CheckEaseExp-NEW/vue-login-backend/login.php",  // Issuer
            "aud" => "http://localhost/CheckEaseExp-NEW/vue-login-backend/login.php",  // Audience
            "iat" => time(),  // Issued at
            "exp" => time() + (60*60),  // Expiration time (1 hour)
            "data" => [
                "id" => $user['id'],
                "firstname" => $user['firstname'],
                "lastname" => $user['lastname'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        $redirectUrl = $user['role'] === 'teacher' ? '/Home' : '/StudentHome';

        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'token' => $jwt, 
            'redirect' => $redirectUrl,  
            'firstname' => $user['firstname'], 
            'lastname' => $user['lastname'],   
            'email' => $user['email'],
            'role' => $user['role'],
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
} catch (PDOException $e) {
 
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>
