<?php
require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        exit;
    }

    // Create Redis session token
    $token = bin2hex(random_bytes(16));
    $ttl = 3600; // 1 hour
    $redis->setex("session:$token", $ttl, json_encode([
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email']
    ]));

    echo json_encode([
        'success' => true,
        'message' => 'Login successful!',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ]
    ]);
} catch (PDOException $ex) {
    echo json_encode(['success' => false, 'message' => 'Database error: '.$ex->getMessage()]);
}
?>
