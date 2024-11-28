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
    // Check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Generate token if not already present
        $token = $user['token'];
        if (empty($token)) {
            $token = bin2hex(random_bytes(16));  // Generate a new token
            $updateTokenStmt = $pdo->prepare("UPDATE users SET token = :token WHERE id = :id");
            $updateTokenStmt->execute(['token' => $token, 'id' => $user['id']]);
        }

        // Determine redirect URL based on role
        $redirectUrl = $user['role'] === 'student' ? '/StudentHome' : '/Home';

        // Respond with user details (including firstname, lastname)
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'redirect' => $redirectUrl,  // Redirect based on the role
            'firstname' => $user['firstname'], // Ensure this column exists in your database
            'lastname' => $user['lastname'],   // Ensure this column exists in your database
        ]);
    } else {
        // Invalid credentials
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
} catch (PDOException $e) {
    // Log the error and return a generic error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>
