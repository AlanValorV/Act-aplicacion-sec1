<?php
include 'vars.php';
$conex = new PDO("sqlite:" . $nombre_fichero);

$stmt_productos = $conex->prepare('SELECT * FROM productos;');
$stmt_productos->execute();
$productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);
//cerrar la bd
$stmt_productos=null;
$conex=null;

$response_data = array(
    'productos' => $productos
);
//responder
http_response_code(200);
echo json_encode($response_data);

?>