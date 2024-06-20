<?php
include 'vars.php';

// Función para insertar un producto
function insertarProducto($data) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($data, $nombre_fichero) {
        // Verificar si vienen los parametros requeridos
        if (empty($data["nombre"])) {
            $reject("Falta nombre");
            return;
        }

        if (empty($data["descripcion"])) {
            $reject("Falta descripción");
            return;
        }

        if (empty($data["precio"])) {
            $reject("Falta precio");
            return;
        }

        if (empty($data["stock"])) {
            $reject("Falta stock");
            return;
        }

        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para insertar el producto
            $sentencia = $conex->prepare("INSERT INTO productos(nombre, descripcion, precio, stock) VALUES(:nombre, :descripcion, :precio, :stock);");
            $sentencia->bindParam(':nombre', $data["nombre"]);
            $sentencia->bindParam(':descripcion', $data["descripcion"]);
            $sentencia->bindParam(':precio', $data["precio"]);
            $sentencia->bindParam(':stock', $data["stock"]);

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

    // Insertar el producto y manejar las promesas
    insertarProducto($data)
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
