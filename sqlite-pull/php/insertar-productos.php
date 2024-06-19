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
if (empty($data["nombre"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta nombre"]);
    exit();
}

if (empty($data["descripcion"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta descripción"]);
    exit();
}

if (empty($data["precio"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta precio"]);
    exit();
}

if (empty($data["stock"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta stock"]);
    exit();
}

$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$producto = [
    "nombre" => $data["nombre"],
    "descripcion" => $data["descripcion"],
    "precio" => $data["precio"],
    "stock" => $data["stock"]
];

try {
    # preparando la consulta
    $sentencia = $conex->prepare("INSERT INTO productos(nombre, descripcion, precio, stock) VALUES(:nombre, :descripcion, :precio, :stock);");
    $resultado = $sentencia->execute($producto);

    if ($resultado) {
        http_response_code(200);
        echo json_encode(["message" => "Datos insertados"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "No se pudo insertar los datos"]);
    }

} catch (PDOException $exc) {
    http_response_code(400);
    echo json_encode(["message" => "Lo siento, ocurrió un error: " . $exc->getMessage()]);
}
?>
