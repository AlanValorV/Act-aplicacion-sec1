<?php
include 'vars.php';

# Verificar si vienen los parametros requeridos
if (empty($_POST["id"])) {
    http_response_code(400);
    exit("Insertar el id a eliminar"); # Terminar el script definitivamente
}

// Conexión a la base de datos
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id_clientes = $_POST["id"];

try {
    # Preparando la consulta para obtener los datos del producto
    $sentencia = $conex->prepare("SELECT * FROM productos WHERE id = :id");
    $sentencia->bindParam(':id', $id_clientes);
    $sentencia->execute();

    # Obtener los datos del producto
    $producto = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        # Mostrar los datos del producto antes de eliminar
        echo "<h2>Datos del producto a eliminar:</h2>";
        echo "ID: " . htmlspecialchars($producto['id']) . "<br>";
        echo "Nombre: " . htmlspecialchars($producto['nombre']) . "<br>";
        echo "Precio: " . htmlspecialchars($producto['precio']) . "<br>";

        # Confirmación de eliminación (Esto debería ser manejado en el front-end con JavaScript)
        echo "<form method='post' action='eliminar_producto.php'>
                <input type='hidden' name='id' value='" . htmlspecialchars($producto['id']) . "'>
                <input type='submit' name='confirmar' value='Confirmar Eliminación'>
              </form>";
    } else {
        http_response_code(404);
        echo "Producto no encontrado";
    }

    # Verificar si la confirmación de eliminación ha sido enviada
    if (isset($_POST['confirmar'])) {
        # Preparando la consulta para eliminar el producto
        $sentencia = $conex->prepare("DELETE FROM productos WHERE id = :id");
        $sentencia->bindParam(':id', $id_clientes);
        $resultado = $sentencia->execute();

        # Verificar si se eliminó el producto correctamente
        if ($resultado) {
            http_response_code(200);
            echo "Datos eliminados";
        } else {
            http_response_code(400);
            echo "No se pudo eliminar el producto";
        }
    }

} catch (PDOException $exc) {
    http_response_code(400);
    echo "Lo siento, ocurrió un error: " . $exc->getMessage();
}
?>
