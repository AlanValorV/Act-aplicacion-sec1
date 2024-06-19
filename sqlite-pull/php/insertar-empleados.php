<?php
include 'vars.php';

// Función para insertar un empleado
function insertarEmpleado($data) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($data, $nombre_fichero) {
        // Verificar si vienen los parametros requeridos
        if (empty($data["nombre"])) {
            $reject("Falta nombre");
            return;
        }

        if (empty($data["direccion"])) {
            $reject("Falta dirección");
            return;
        }

        if (empty($data["correo"])) {
            $reject("Falta correo");
            return;
        }

        if (empty($data["telefono"])) {
            $reject("Falta teléfono");
            return;
        }

        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para insertar el empleado
            $sentencia = $conex->prepare("INSERT INTO empleados(nombre, direccion, correo, telefono) VALUES(:nombre, :direccion, :correo, :telefono);");
            $sentencia->bindParam(':nombre', $data["nombre"]);
            $sentencia->bindParam(':direccion', $data["direccion"]);
            $sentencia->bindParam(':correo', $data["correo"]);
            $sentencia->bindParam(':telefono', $data["telefono"]);
            
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

    // Insertar el empleado y manejar las promesas
    insertarEmpleado($data)
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
