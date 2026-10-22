
<?php
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'status' => 'ok',
    'service' => 'portfolio-backend',
    'message' => 'PHP backend is running'
], JSON_UNESCAPED_UNICODE);
