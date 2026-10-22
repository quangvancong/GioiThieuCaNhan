
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query('SELECT 1 AS connected');
    $result = $stmt->fetch();

    echo json_encode([
        'status' => 'ok',
        'database' => 'connected',
        'result' => (int) $result['connected']
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Database health check failed: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Database health check failed'
    ], JSON_UNESCAPED_UNICODE);
}
