<?php
include 'vars.php';

try {
    $conex = new PDO("sqlite:" . $nombre_fichero);
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_clientes = $conex->prepare('SELECT * FROM clientes;');
    $stmt_clientes->execute();
    $clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

    $stmt_clientes = null;
    $conex = null;

    $response_data = array(
        'clientes' => $clientes
    );

    http_response_code(200);
    echo json_encode($response_data);

} catch (PDOException $exc) {
    http_response_code(500);
    echo json_encode(array('message' => 'Error al obtener clientes: ' . $exc->getMessage()));
}
?>
