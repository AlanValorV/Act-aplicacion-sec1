<?php
include 'vars.php';

// Función para insertar una venta
function insertarVenta($data) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($data, $nombre_fichero) {
        // Verificar si vienen los parametros requeridos
        if (empty($data["fecha"])) {
            $reject("Falta la fecha");
            return;
        }

        if (empty($data["total"])) {
            $reject("Falta total");
            return;
        }

        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para insertar la venta
            $sentencia = $conex->prepare("INSERT INTO ventas(fecha, total) VALUES(:fecha, :total);");
            $sentencia->bindParam(':fecha', $data["fecha"]);
            $sentencia->bindParam(':total', $data["total"]);

            $resultado = $sentencia->execute();

            // Verificar si se insertaron los datos correctamente
            if ($resultado) {
                $resolve("Datos insertados");
            } else {
                $reject("No se pudo insertar los datos");
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

    // Insertar la venta y manejar las promesas
    insertarVenta($data)
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
