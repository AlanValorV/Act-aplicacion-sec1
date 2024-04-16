<?php
include 'vars.php';

#verificar si vienen losparametros requeridos
if (empty($_POST["nombre"])) {
    http_response_code(400);
	exit("Falta nombre"); #Terminar el script definitivamente
}

if (empty($_POST["descripcion"])) {
    http_response_code(400);
	exit("falta descripcion"); #Terminar el script definitivamente
}
if (empty($_POST["precio"])) {
    http_response_code(400);
	exit("falta precio"); #Terminar el script definitivamente
}
if (empty($_POST["stock"])) {
    http_response_code(400);
	exit("falta stock"); #Terminar el script definitivamente
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$productos=[
    "nombre"=> $_POST["nombre"],
    "descripcion"=> $_POST["descripcion"],
    "precio"=> $_POST["precio"],
    "stock"=> $_POST["stock"]
];
try{
    # preparando la cosnaulta
    $sentencia = $conex->prepare("insert into productos(nombre, descripcion, precio, stock) values(:nombre, :descripcion, :precio, :stock);");
    $resultado = $sentencia->execute($productos);
    http_response_code(200);
    echo "datos insertados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();

}

?>