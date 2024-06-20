<?php
include 'vars.php';

// Función para eliminar la venta por su ID
function eliminarVenta($id) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($id, $nombre_fichero) {
        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para eliminar la venta
            $sentencia = $conex->prepare("DELETE FROM ventas WHERE id = :id");
            $sentencia->bindParam(':id', $id);
            $resultado = $sentencia->execute();

            // Verificar si se eliminó la venta correctamente
            if ($resultado) {
                $resolve("Venta eliminada correctamente");
            } else {
                $reject("No se pudo eliminar la venta");
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

    // Verificar si se ha enviado el ID de la venta
    if (empty($data["id"])) {
        http_response_code(400);
        echo json_encode(["message" => "Insertar el ID a eliminar"]);
        exit();
    }

    $id_venta = $data["id"];

    // Eliminar la venta y manejar las promesas
    eliminarVenta($id_venta)
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
