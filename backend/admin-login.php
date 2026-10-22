<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);

    echo json_encode([
        'status' => 'error',
        'message' => 'Chi chap nhan POST'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if ($username === '' || $password === '') {
    http_response_code(400);

    echo json_encode([
        'status' => 'error',
        'message' => 'Vui long nhap tai khoan va mat khau'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $pdo->prepare(
    'SELECT id, username, password_hash
     FROM admin_users
     WHERE username = :username
     LIMIT 1'
);

$stmt->execute(['username' => $username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    http_response_code(401);

    echo json_encode([
        'status' => 'error',
        'message' => 'Tai khoan hoac mat khau khong chinh xac'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

session_regenerate_id(true);

$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_username'] = $admin['username'];

echo json_encode([
    'status' => 'ok',
    'message' => 'Dang nhap thanh cong',
    'username' => $admin['username']
], JSON_UNESCAPED_UNICODE);
