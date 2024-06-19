<?php
include 'vars.php';

try {

    $conex = new PDO("sqlite:" . $nombre_fichero);
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_productos = $conex->prepare('SELECT * FROM productos;');
    $stmt_productos->execute();
    $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

    $stmt_productos = null;
    $conex = null;
    $response_data = array(
        'productos' => $productos
    );
    http_response_code(200);
    echo json_encode($response_data);

} catch (PDOException $exc) {
    http_response_code(500);
    echo json_encode(array('message' => 'Error al obtener productos: ' . $exc->getMessage()));
}
?>
