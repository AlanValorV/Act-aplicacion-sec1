<?php
include 'vars.php';

// Función para eliminar el empleado por su ID
function eliminarEmpleado($id) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($id, $nombre_fichero) {
        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para eliminar el empleado
            $sentencia = $conex->prepare("DELETE FROM empleados WHERE id = :id");
            $sentencia->bindParam(':id', $id);
            $resultado = $sentencia->execute();

            // Verificar si se eliminó el empleado correctamente
            if ($resultado) {
                $resolve("Empleado eliminado correctamente");
            } else {
                $reject("No se pudo eliminar el empleado");
            }

        } catch (PDOException $exc) {
            // Error en la consulta
            $reject("Error en la consulta: " . $exc->getMessage());
        } finally {
            // Cerrar la conexión
            $conex = null;
        }
    });
}

// Manejar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer los datos JSON enviados
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se ha enviado el ID del empleado
    if (empty($data["id"])) {
        http_response_code(400);
        echo json_encode(["message" => "Insertar el ID a eliminar"]);
        exit();
    }

    $id_empleado = $data["id"];

    // Eliminar el empleado y manejar las promesas
    eliminarEmpleado($id_empleado)
        ->then(function($message) {
            http_response_code(200);
            echo json_encode(["message" => $message]);
        })
        ->catch(function($error) {
            http_response_code(400);
            echo json_encode(["message" => $error]);
        });
}
?>
