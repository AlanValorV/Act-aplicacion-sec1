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

if (empty($data["direccion"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta direccion"]);
    exit();
}
if (empty($data["correo"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta correo"]);
    exit();
}
if (empty($data["telefono"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta telefono"]);
    exit();
}

$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$clientes = [
    "nombre" => $data["nombre"],
    "direccion" => $data["direccion"],
    "correo" => $data["correo"],
    "telefono" => $data["telefono"]
];

try {
    # preparando la consulta
    $sentencia = $conex->prepare("INSERT INTO clientes(nombre, direccion, correo, telefono) VALUES(:nombre, :direccion, :correo, :telefono);");
    $resultado = $sentencia->execute($clientes);

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
