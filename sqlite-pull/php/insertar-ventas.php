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
if (empty($data["fecha"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta la fecha"]);
    exit();
}

if (empty($data["total"])) {
    http_response_code(400);
    echo json_encode(["message" => "Falta total"]);
    exit();
}

$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$venta = [
    "fecha" => $data["fecha"],
    "total" => $data["total"]
];

try {
    # preparando la consulta
    $sentencia = $conex->prepare("INSERT INTO ventas(fecha, total) VALUES(:fecha, :total);");
    $resultado = $sentencia->execute($venta);

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
