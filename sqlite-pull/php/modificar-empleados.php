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

if (empty($data["apellido"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar apellido"]);
    exit();
}

if (empty($data["puesto"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar puesto"]);
    exit();
}

if (empty($data["salario"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar salario"]);
    exit();
}

if (empty($data["fecha"])) {
    http_response_code(400);
    echo json_encode(["message" => "Insertar fecha de contratacion"]);
    exit();
}

$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$empleado = [
    "id" => $data["id"],
    "nombre" => $data["nombre"],
    "apellido" => $data["apellido"],
    "puesto" => $data["puesto"],
    "salario" => $data["salario"],
    "fecha" => $data["fecha"]
];

try {
    # preparando la consulta
    $sentencia = $conex->prepare("UPDATE empleados SET nombre=:nombre, apellido=:apellido, puesto=:puesto, salario=:salario, fecha=:fecha WHERE id=:id;");
    $resultado = $sentencia->execute($empleado);

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
