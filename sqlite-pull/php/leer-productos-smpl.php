<?php
include 'vars.php';

// Función para obtener todos los productos
function obtenerProductos() {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($nombre_fichero) {
        try {
            $conex = new PDO("sqlite:" . $nombre_fichero);
            $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt_productos = $conex->prepare('SELECT * FROM productos;');
            $stmt_productos->execute();
            $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

            $stmt_productos = null;
            $conex = null;

            $resolve($productos);

        } catch (PDOException $exc) {
            $reject("Error al obtener productos: " . $exc->getMessage());
        }
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtenerProductos()
        ->then(function($productos) {
            $response_data = array(
                'productos' => $productos
            );
            http_response_code(200);
            echo json_encode($response_data);
        })
        ->catch(function($error) {
            http_response_code(500);
            echo json_encode(array('message' => $error));
        });
}
?>
