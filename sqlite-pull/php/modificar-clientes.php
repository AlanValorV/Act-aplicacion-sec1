<?php
include 'vars.php';

// Función para modificar un cliente
function modificarCliente($data) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($data, $nombre_fichero) {
        // Verificar si vienen los parametros requeridos
        if (empty($data["id"])) {
            $reject("Insertar id");
            return;
        }

        if (empty($data["nombre"])) {
            $reject("Insertar nombre");
            return;
        }

        if (empty($data["direccion"])) {
            $reject("Insertar direccion");
            return;
        }

        if (empty($data["correo"])) {
            $reject("Insertar correo");
            return;
        }

        if (empty($data["telefono"])) {
            $reject("Insertar telefono");
            return;
        }

        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para modificar el cliente
            $sentencia = $conex->prepare("UPDATE clientes SET nombre=:nombre, direccion=:direccion, correo=:correo, telefono=:telefono WHERE id=:id;");
            $sentencia->bindParam(':id', $data["id"]);
            $sentencia->bindParam(':nombre', $data["nombre"]);
            $sentencia->bindParam(':direccion', $data["direccion"]);
            $sentencia->bindParam(':correo', $data["correo"]);
            $sentencia->bindParam(':telefono', $data["telefono"]);

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

    // Modificar el cliente y manejar las promesas
    modificarCliente($data)
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
