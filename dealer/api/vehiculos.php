<?php
// ═══════════════════════════════════════════
// api/vehiculos.php
// Endpoint JSON — responde peticiones AJAX
// ═══════════════════════════════════════════

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once "../config/conexion.php";

try {
    $db   = Conexion::conectar();
    $accion = $_GET['accion'] ?? 'listar';

    // ── GET: listar todos ──────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $accion === 'listar') {
        $stmt = $db->query("SELECT * FROM vehiculos ORDER BY id DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'total'   => count($data),
            'data'    => $data
        ]);
    }

    // ── GET: buscar por término ────────────
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $accion === 'buscar') {
        $q    = '%' . ($_GET['q'] ?? '') . '%';
        $stmt = $db->prepare(
            "SELECT * FROM vehiculos
             WHERE marca LIKE :q OR modelo LIKE :q OR estado LIKE :q
             ORDER BY id DESC"
        );
        $stmt->execute([':q' => $q]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'total'   => count($data),
            'data'    => $data
        ]);
    }

    // ── GET: obtener uno por ID ────────────
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $accion === 'obtener') {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare("SELECT * FROM vehiculos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'mensaje' => 'Vehículo no encontrado']);
        }
    }

    // ── POST: guardar nuevo ────────────────
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'guardar') {
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $nombre = time() . '_' . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../uploads/" . $nombre);
            $imagen = $nombre;
        }

        $stmt = $db->prepare(
            "INSERT INTO vehiculos (marca, modelo, anio, precio, estado, imagen)
             VALUES (:marca, :modelo, :anio, :precio, :estado, :imagen)"
        );
        $stmt->execute([
            ':marca'  => $_POST['marca'],
            ':modelo' => $_POST['modelo'],
            ':anio'   => $_POST['anio'],
            ':precio' => $_POST['precio'],
            ':estado' => $_POST['estado'],
            ':imagen' => $imagen,
        ]);

        echo json_encode([
            'success' => true,
            'mensaje' => 'Vehículo creado correctamente',
            'id'      => $db->lastInsertId()
        ]);
    }

    // ── POST: eliminar ─────────────────────
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'eliminar') {
        $id   = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare("DELETE FROM vehiculos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode([
            'success' => true,
            'mensaje' => 'Vehículo eliminado correctamente'
        ]);
    }

    else {
        http_response_code(400);
        echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error del servidor: ' . $e->getMessage()
    ]);
}