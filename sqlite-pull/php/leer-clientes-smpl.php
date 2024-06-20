<?php
include 'vars.php';

// Función para obtener todos los clientes
function obtenerClientes() {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($nombre_fichero) {
        try {
            $conex = new PDO("sqlite:" . $nombre_fichero);
            $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt_clientes = $conex->prepare('SELECT * FROM clientes;');
            $stmt_clientes->execute();
            $clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);


            $stmt_clientes = null;
            $conex = null;

            $resolve($clientes);

        } catch (PDOException $exc) {

            $reject("Error al obtener clientes: " . $exc->getMessage());
        }
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    obtenerClientes()
        ->then(function($clientes) {
            $response_data = array(
                'clientes' => $clientes
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
