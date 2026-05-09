<?php
// api.php - Backend para consultar datos de Clubes y Aliados
require_once 'db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'get_estados') {
    // Solo trae los estados que tienen al menos un club registrado
    $stmt = $pdo->query("SELECT DISTINCT e.id, e.nombre FROM estados e JOIN clubes c ON e.id = c.estado_id ORDER BY e.nombre ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'get_all_clubes') {
    // Trae todos los clubes para el selector de concesionarios
    $stmt = $pdo->query("SELECT id, nombre, estado_id FROM clubes ORDER BY nombre ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'get_club_concesionarios') {
    $club_id = (int)($_GET['club_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT id, nombre, rubro, telefono, whatsapp, horario FROM concesionarios_internos WHERE club_id = :club_id ORDER BY rubro ASC, nombre ASC");
    $stmt->execute(['club_id' => $club_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'get_estados_aliados') {
    // Solo trae los estados que tienen al menos un aliado comercial
    $stmt = $pdo->query("SELECT DISTINCT e.id, e.nombre FROM estados e JOIN aliados_comerciales a ON e.id = a.estado_id ORDER BY e.nombre ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'get_aliados_por_estado') {
    $estado_id = (int)($_GET['estado_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT id, nombre, descuento_info, direccion_mapas, whatsapp, categoria FROM aliados_comerciales WHERE estado_id = :estado_id ORDER BY categoria ASC, nombre ASC");
    $stmt->execute(['estado_id' => $estado_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'get_clubes_grouped') {
    // Trae todos los clubes agrupados por estado
    $stmt = $pdo->query("
        SELECT c.*, e.nombre as estado_nombre 
        FROM clubes c 
        JOIN estados e ON (c.estado_id = e.id)
        ORDER BY e.nombre ASC, c.nombre ASC
    ");
    $clubesData = $stmt->fetchAll();
    
    $estadosAgrupados = [];
    foreach ($clubesData as $row) {
        $estado = $row['estado_nombre'];
        if (!isset($estadosAgrupados[$estado])) {
            $estadosAgrupados[$estado] = [
                'estado_nombre' => $estado,
                'clubes' => []
            ];
        }
        
        $stmtConc = $pdo->prepare("SELECT id, nombre, rubro, telefono, whatsapp, horario FROM concesionarios_internos WHERE club_id = :cid");
        $stmtConc->execute(['cid' => $row['id']]);
        $row['concesionarios'] = $stmtConc->fetchAll();
        
        $estadosAgrupados[$estado]['clubes'][] = $row;
    }
    
    echo json_encode(array_values($estadosAgrupados));
    exit;
}

if ($action === 'get_datos') {
    $estado_id = isset($_GET['estado_id']) ? (int)$_GET['estado_id'] : 0;
    
    if ($estado_id > 0) {
        $response = ['clubes' => [], 'aliados' => []];

        // Obtener clubes
        $stmtClub = $pdo->prepare("SELECT id, nombre, direccion_mapas, logo_url FROM clubes WHERE estado_id = :estado_id");
        $stmtClub->execute(['estado_id' => $estado_id]);
        $clubes = $stmtClub->fetchAll();

        foreach ($clubes as &$club) {
            // Obtener concesionarios del club
            $stmtConc = $pdo->prepare("SELECT id, nombre, rubro, telefono, whatsapp, horario FROM concesionarios_internos WHERE club_id = :club_id");
            $stmtConc->execute(['club_id' => $club['id']]);
            $club['concesionarios'] = $stmtConc->fetchAll();
        }
        $response['clubes'] = $clubes;

        // Obtener aliados comerciales
        $stmtAliados = $pdo->prepare("SELECT id, nombre, descuento_info, direccion_mapas, whatsapp, categoria FROM aliados_comerciales WHERE estado_id = :estado_id");
        $stmtAliados->execute(['estado_id' => $estado_id]);
        $response['aliados'] = $stmtAliados->fetchAll();

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'ID de estado no válido']);
    }
    exit;
}

echo json_encode(['error' => 'Acción no válida']);
?>