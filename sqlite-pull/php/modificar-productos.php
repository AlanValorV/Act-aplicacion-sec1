<?php
include 'vars.php';

// Configurar los encabezados para aceptar JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Leer los datos JSON enviados
$data = json_decode(file_get_contents('php://input'), true);

# Verificar si vienen los parametros requeridos
if (empty($data["id"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar id"]);
    exit();
}

if (empty($data["nombre"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar nombre"]);
    exit();
}

if (empty($data["descripcion"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar descripcion"]);
    exit();
}

if (empty($data["precio"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar precio"]);
    exit();
}

if (empty($data["stock"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar stock disponible"]);
    exit();
}

$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$producto = [
    "id" => $data["id"],
    "nombre" => $data["nombre"],
    "descripcion" => $data["descripcion"],
    "precio" => $data["precio"],
    "stock" => $data["stock"]
];

try {
    # preparando la consulta
    $sentencia = $conex->prepare("UPDATE productos SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock WHERE id=:id;");
    $resultado = $sentencia->execute($producto);

    if ($resultado) {
        http_response_code(200);
        echo json_encode(["message" => "Datos actualizados"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "No se pudo actualizar los datos"]);
    }

} catch (PDOException $exc) {
    http_response_code(400);
    echo json_encode(["message" => "Lo siento, ocurrió un error: " . $exc->getMessage()]);
}
?>
