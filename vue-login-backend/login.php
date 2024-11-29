<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include 'db.php';

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
        $token = $user['token'];
        if (empty($token)) {
            $token = bin2hex(random_bytes(16));  // Generate a new token
            $updateTokenStmt = $pdo->prepare("UPDATE users SET token = :token WHERE id = :id");
            $updateTokenStmt->execute(['token' => $token, 'id' => $user['id']]);
        }

        $redirectUrl = $user['role'] === 'teacher' ? '/Home' : '/StudentHome';

        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
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
    // Log the error and return a generic error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>
