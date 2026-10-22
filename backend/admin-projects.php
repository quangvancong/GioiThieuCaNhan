<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Vui long dang nhap'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    // READ: Lay danh sach du an
    if ($method === 'GET') {
        $stmt = $pdo->query(
            'SELECT id, title, description, technologies,
                    project_url, image, created_at
             FROM projects
             ORDER BY id DESC'
        );

        echo json_encode([
            'status' => 'ok',
            'data' => $stmt->fetchAll()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Doc du lieu JSON gui len
    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Du lieu JSON khong hop le'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // CREATE: Them du an
    if ($method === 'POST') {
        $title = trim($data['title'] ?? '');

        if ($title === '') {
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ten du an khong duoc de trong'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO projects
             (title, description, technologies, project_url, image)
             VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $title,
            trim($data['description'] ?? ''),
            trim($data['technologies'] ?? ''),
            trim($data['project_url'] ?? ''),
            trim($data['image'] ?? '')
        ]);

        http_response_code(201);
        echo json_encode([
            'status' => 'ok',
            'message' => 'Them du an thanh cong',
            'id' => (int) $pdo->lastInsertId()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // UPDATE va DELETE can ID hop le
    $id = filter_var(
        $data['id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id || $id < 1) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'ID du an khong hop le'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // UPDATE: Sua du an
    if ($method === 'PUT') {
        $title = trim($data['title'] ?? '');

        if ($title === '') {
            http_response_code(422);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ten du an khong duoc de trong'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmt = $pdo->prepare(
            'UPDATE projects
             SET title = ?, description = ?, technologies = ?,
                 project_url = ?, image = ?
             WHERE id = ?'
        );

        $stmt->execute([
            $title,
            trim($data['description'] ?? ''),
            trim($data['technologies'] ?? ''),
            trim($data['project_url'] ?? ''),
            trim($data['image'] ?? ''),
            $id
        ]);

        echo json_encode([
            'status' => 'ok',
            'message' => $stmt->rowCount() > 0
                ? 'Cap nhat du an thanh cong'
                : 'Khong co thay doi hoac du an khong ton tai'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // DELETE: Xoa du an
    if ($method === 'DELETE') {
        $stmt = $pdo->prepare(
            'DELETE FROM projects WHERE id = ?'
        );
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Khong tim thay du an'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode([
            'status' => 'ok',
            'message' => 'Xoa du an thanh cong'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header('Allow: GET, POST, PUT, DELETE');
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Phuong thuc khong duoc ho tro'
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log('Admin projects API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Loi xu ly du lieu du an'
    ], JSON_UNESCAPED_UNICODE);
}
