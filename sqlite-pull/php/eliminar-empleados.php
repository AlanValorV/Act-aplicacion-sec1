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
    echo json_encode(["message" => "Insertar el id a eliminar"]);
    exit();
}

// Conexión a la base de datos
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id_empleados = $data["id"];

try {
    # Preparando la consulta
    $sentencia = $conex->prepare("DELETE FROM empleados WHERE id = :id");
    $sentencia->bindParam(':id', $id_empleados);
    $resultado = $sentencia->execute();

    // Verificar si se eliminó el empleado correctamente
    if ($resultado) {
        http_response_code(200);
        echo json_encode(["message" => "Datos eliminados"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "No se pudo eliminar el empleado"]);
    }

} catch (PDOException $exc) {
    http_response_code(400);
    echo json_encode(["message" => "Lo siento, ocurrió un error: " . $exc->getMessage()]);
}
?>
