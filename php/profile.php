<?php
require_once __DIR__ . '/db.php';

$token = $_POST['token'] ?? '';
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'Token is required']);
    exit;
}

// Check session in Redis
$session = $redis->get("session:$token");
if (!$session) {
    echo json_encode(['success' => false, 'message' => 'Session expired or invalid']);
    exit;
}

$userData = json_decode($session, true);
$userId = $userData['id'];

$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;

try {
    if ($age || $dob || $contact) {
        $stmt = $pdo->prepare("UPDATE users SET age = :age, dob = :dob, contact = :contact WHERE id = :id");
        $stmt->execute([
            ':age' => $age,
            ':dob' => $dob,
            ':contact' => $contact,
            ':id' => $userId
        ]);
    }

    $stmt = $pdo->prepare("SELECT username, email, age, dob, contact FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'user' => $user]);
} catch (PDOException $ex) {
    echo json_encode(['success' => false, 'message' => 'Database error: '.$ex->getMessage()]);
}

?>
