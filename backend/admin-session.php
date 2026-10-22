<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);

    echo json_encode([
        'status' => 'error',
        'message' => 'Vui lòng đăng nhập lại'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

echo json_encode([
    'status' => 'ok',
    'authenticated' => true,
    'username' => $_SESSION['admin_username'] ?? ''
], JSON_UNESCAPED_UNICODE);
