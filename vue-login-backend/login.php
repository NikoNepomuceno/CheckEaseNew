<?php
require __DIR__ . '/vendor/autoload.php'; 

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

use Firebase\JWT\JWT;
include 'db.php';

$secret_key = getenv('JWT_SECRET_KEY'); 
if (!$secret_key) {
    echo json_encode(['success' => false, 'message' => 'Missing JWT secret key.']);
    exit;
}

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

    if ($user) {
       
        if (password_verify($password, $user['password'])) {
            $payload = [
                "iss" => "http://localhost/CheckEaseExp-NEW/vue-login-backend/login.php",
                "aud" => "http://localhost/CheckEaseExp-NEW/vue-login-backend/login.php",
                "iat" => time(),
                "exp" => time() + (60 * 60), 
                "data" => [
                    "id" => $user['id'],
                    "firstname" => $user['firstname'],
                    "lastname" => $user['lastname'],
                    "email" => $user['email'],
                    "role" => $user['role']
                ]
            ];

            $jwt = JWT::encode($payload, $secret_key, 'HS256');
            $updateStmt = $pdo->prepare("UPDATE users SET token = :token WHERE email = :email");
            $updateResult = $updateStmt->execute(['token' => $jwt, 'email' => $email]);

            if (!$updateResult) {
                error_log("Failed to update token in database: " . implode(", ", $updateStmt->errorInfo()));
                echo json_encode(['success' => false, 'message' => 'Failed to save token.']);
                exit;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'token' => $jwt,
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>
