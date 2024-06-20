<?php
include 'vars.php';

// Función para modificar una venta
function modificarVenta($data) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($data, $nombre_fichero) {
        // Verificar si vienen los parametros requeridos
        if (empty($data["id"])) {
            $reject("Insertar id");
            return;
        }

        if (empty($data["fecha"])) {
            $reject("Insertar fecha a modificar");
            return;
        }

        if (empty($data["total"])) {
            $reject("Insertar el total de la venta");
            return;
        }

        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para modificar la venta
            $sentencia = $conex->prepare("UPDATE ventas SET fecha=:fecha, total=:total WHERE id=:id;");
            $sentencia->bindParam(':id', $data["id"]);
            $sentencia->bindParam(':fecha', $data["fecha"]);
            $sentencia->bindParam(':total', $data["total"]);

            $resultado = $sentencia->execute();

            // Verificar si se actualizaron los datos correctamente
            if ($resultado) {
                $resolve("Datos actualizados");
            } else {
                $reject("No se pudo actualizar los datos");
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

    // Modificar la venta y manejar las promesas
    modificarVenta($data)
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
