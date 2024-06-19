<?php
include 'vars.php';

// Función para obtener todos los empleados
function obtenerEmpleados() {
    global $nombre_fichero;

    return new Promise(function($resolve, $reject) use ($nombre_fichero) {
        try {
            $conex = new PDO("sqlite:" . $nombre_fichero);
            $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt_empleados = $conex->prepare('SELECT * FROM empleados;');
            $stmt_empleados->execute();
            $empleados = $stmt_empleados->fetchAll(PDO::FETCH_ASSOC);

            $stmt_empleados = null;
            $conex = null;

            $resolve($empleados);

        } catch (PDOException $exc) {
            $reject("Error al obtener empleados: " . $exc->getMessage());
        }
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    obtenerEmpleados()
        ->then(function($empleados) {
            $response_data = array(
                'empleados' => $empleados
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
