<?php
include 'vars.php';

#verificar si vienen losparametros requeridos
if (empty($_POST["id"])) {
    http_response_code(400);
	exit("Insertar id"); #Terminar el script definitivamente
}

if (empty($_POST["nombre"])) {
    http_response_code(400);
	exit("Insertar nombre"); 
}

if (empty($_POST["descripcion"])) {
    http_response_code(400);
	exit("insertar descripcion"); 
}
if (empty($_POST["precio"])) {
    http_response_code(400);
	exit("insertar precio"); 
}
if (empty($_POST["stock"])) {
    http_response_code(400);
	exit("insertar stock disponible"); 
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$productos=[
    "id"=> $_POST["id"],
    "nombre"=> $_POST["nombre"],
    "descripcion"=> $_POST["descripcion"],
    "precio"=> $_POST["precio"],
    "stock"=> $_POST["stock"]
    
];
try{
    # preparando la consulta
    $sentencia = $conex->prepare("update productos set nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock where id=:id;");
    $resultado = $sentencia->execute($productos);
    http_response_code(200);
    echo "datos actualizados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();
}

?>