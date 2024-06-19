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

if (empty($data["direccion"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar direccion"]);
    exit();
}

if (empty($data["correo"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar correo"]);
    exit();
}

if (empty($data["telefono"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar telefono"]);
    exit();
}

$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$cliente = [
    "id" => $data["id"],
    "nombre" => $data["nombre"],
    "direccion" => $data["direccion"],
    "correo" => $data["correo"],
    "telefono" => $data["telefono"]
];

try {
    # preparando la consulta
    $sentencia = $conex->prepare("UPDATE clientes SET nombre=:nombre, direccion=:direccion, correo=:correo, telefono=:telefono WHERE id=:id;");
    $resultado = $sentencia->execute($cliente);

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
