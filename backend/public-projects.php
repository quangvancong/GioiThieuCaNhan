<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query(
        'SELECT id, title, description, technologies, project_url, image
         FROM projects
         ORDER BY id DESC'
    );

    echo json_encode([
        'status' => 'ok',
        'data' => $stmt->fetchAll()
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log('Public projects API error: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Khong the tai danh sach du an'
    ], JSON_UNESCAPED_UNICODE);
}
