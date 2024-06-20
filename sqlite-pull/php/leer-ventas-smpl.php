<?php
include 'vars.php';

// Función para obtener todas las ventas
function obtenerVentas() {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($nombre_fichero) {
        try {
            $conex = new PDO("sqlite:" . $nombre_fichero);
            $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt_ventas = $conex->prepare('SELECT * FROM ventas;');
            $stmt_ventas->execute();
            $ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

            $stmt_ventas = null;
            $conex = null;

            $resolve($ventas);

        } catch (PDOException $exc) {
            // Rechazar la promesa en caso de error
            $reject("Error al obtener ventas: " . $exc->getMessage());
        }
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtenerVentas()
        ->then(function($ventas) {

            $response_data = array(
                'ventas' => $ventas
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
