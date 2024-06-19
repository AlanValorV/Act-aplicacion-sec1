<?php
include 'vars.php';

try {

    $conex = new PDO("sqlite:" . $nombre_fichero);
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_empleados = $conex->prepare('SELECT * FROM empleados;');
    $stmt_empleados->execute();
    $empleados = $stmt_empleados->fetchAll(PDO::FETCH_ASSOC);

    $stmt_empleados = null;
    $conex = null;

    $response_data = array(
        'empleados' => $empleados
    );

    http_response_code(200);
    echo json_encode($response_data);

} catch (PDOException $exc) {
    http_response_code(500);
    echo json_encode(array('message' => 'Error al obtener empleados: ' . $exc->getMessage()));
}
?>
