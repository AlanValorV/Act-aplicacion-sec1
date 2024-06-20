<?php
include 'vars.php';

// Función para obtener los datos del cliente por su ID
function obtenerCliente($id) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($id, $nombre_fichero) {
        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para obtener los datos del cliente
            $sentencia = $conex->prepare("SELECT * FROM clientes WHERE id = :id");
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();

            // Obtener los datos del cliente
            $cliente = $sentencia->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                // Responder con los datos del cliente
                $resolve($cliente);
            } else {
                // Cliente no encontrado
                $reject("Cliente no encontrado");
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

// Función para eliminar el cliente por su ID
function eliminarCliente($id) {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($id, $nombre_fichero) {
        // Conexión a la base de datos
        $conex = new PDO("sqlite:" . $nombre_fichero);
        $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Preparando la consulta para eliminar el cliente
            $sentencia = $conex->prepare("DELETE FROM clientes WHERE id = :id");
            $sentencia->bindParam(':id', $id);
            $resultado = $sentencia->execute();

            // Verificar si se eliminó el cliente correctamente
            if ($resultado) {
                $resolve("Cliente eliminado correctamente");
            } else {
                $reject("No se pudo eliminar el cliente");
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
    $id_cliente = $_POST['id'];

    // Verificar si se ha enviado la confirmación de eliminación
    if (isset($_POST['confirmar'])) {
        eliminarCliente($id_cliente)
            ->then(function($message) {
                http_response_code(200);
                echo $message;
            })
            ->catch(function($error) {
                http_response_code(400);
                echo $error;
            });
    } else {
        obtenerCliente($id_cliente)
            ->then(function($cliente) {
                // Mostrar los datos del cliente antes de eliminar
                echo "<h2>Datos del cliente a eliminar:</h2>";
                echo "ID: " . htmlspecialchars($cliente['id']) . "<br>";
                echo "Nombre: " . htmlspecialchars($cliente['nombre']) . "<br>";
                echo "Dirección: " . htmlspecialchars($cliente['direccion']) . "<br>";
                echo "Correo: " . htmlspecialchars($cliente['correo']) . "<br>";
                echo "Teléfono: " . htmlspecialchars($cliente['telefono']) . "<br>";

                // Formulario para confirmar la eliminación
                echo "<form method='post' action='eliminar_cliente.php'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($cliente['id']) . "'>
                        <input type='submit' name='confirmar' value='Confirmar Eliminación'>
                      </form>";
            })
            ->catch(function($error) {
                http_response_code(404);
                echo $error;
            });
    }
}
?>

