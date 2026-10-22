
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query(
        'SELECT id, full_name, job_title, bio, email, address, avatar
         FROM profile
         ORDER BY id ASC
         LIMIT 1'
    );

    $profile = $stmt->fetch();

    if (!$profile) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Chưa có thông tin cá nhân'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'status' => 'ok',
        'data' => $profile
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log('Profile query failed: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Không thể tải thông tin cá nhân'
    ], JSON_UNESCAPED_UNICODE);
}
