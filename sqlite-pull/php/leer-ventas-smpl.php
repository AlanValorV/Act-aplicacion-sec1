<?php
include 'vars.php';

try {

    $conex = new PDO("sqlite:" . $nombre_fichero);
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_ventas = $conex->prepare('SELECT * FROM ventas;');
    $stmt_ventas->execute();
    $ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

    $stmt_ventas = null;
    $conex = null;

    $response_data = array(
        'ventas' => $ventas
    );

    http_response_code(200);
    echo json_encode($response_data);

} catch (PDOException $exc) {
    http_response_code(500);
    echo json_encode(array('message' => 'Error al obtener ventas: ' . $exc->getMessage()));
}
?>
