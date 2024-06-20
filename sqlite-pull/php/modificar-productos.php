<?php
include 'vars.php';

// Función para modificar un producto
function modificarProducto($data) {
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

        if (empty($data["descripcion"])) {
            $reject("Insertar descripcion");
            return;
        }

        if (empty($data["precio"])) {
            $reject("Insertar precio");
            return;
        }

        if (empty($data["stock"])) {
            $reject("Insertar stock disponible");
            return;
        }

        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para modificar el producto
            $sentencia = $conex->prepare("UPDATE productos SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock WHERE id=:id;");
            $sentencia->bindParam(':id', $data["id"]);
            $sentencia->bindParam(':nombre', $data["nombre"]);
            $sentencia->bindParam(':descripcion', $data["descripcion"]);
            $sentencia->bindParam(':precio', $data["precio"]);
            $sentencia->bindParam(':stock', $data["stock"]);

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

    // Modificar el producto y manejar las promesas
    modificarProducto($data)
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
