<?php
include 'vars.php';


if (empty($_POST["id"])) {
    http_response_code(400);
	exit("Id no insertado"); #Terminar el script definitivamente
}
#verificar si vienen los parametros requeridos
if (empty($_POST["fecha"])) {
    http_response_code(400);
	exit("Insertar fecha a modificar"); 
}
if (empty($_POST["total"])) {
    http_response_code(400);
	exit("Insertar el total de la venta"); 
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ventas=[
    "id" => $_POST["id"],
    "fecha"=> $_POST["fecha"],
    "total"=> $_POST["total"]
];
try{
    # preparando la consulta
    $sentencia = $conex->prepare("update ventas set fecha=:fecha, total=:total where id=:id;");
    $resultado = $sentencia->execute($ventas);
    http_response_code(200);
    echo "datos actualizados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();
}

?>