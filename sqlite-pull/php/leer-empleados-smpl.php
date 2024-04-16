<?php
include 'vars.php';
$conex = new PDO("sqlite:" . $nombre_fichero);

$stmt_empleados = $conex->prepare('SELECT * FROM empleados;');
$stmt_empleados->execute();
$empleados = $stmt_empleados->fetchAll(PDO::FETCH_ASSOC);
//cerrar la bd
$stmt_empleados=null;
$conex=null;

$response_data = array(
    'empleados' => $empleados
);
//responder
http_response_code(200);
echo json_encode($response_data);

?>