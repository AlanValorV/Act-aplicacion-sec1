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

if (empty($_POST["direccion"])) {
    http_response_code(400);
	exit("insertar direccion"); 
}
if (empty($_POST["correo"])) {
    http_response_code(400);
	exit("insertar correo"); 
}
if (empty($_POST["telefono"])) {
    http_response_code(400);
	exit("insertar telefono"); 
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$clientes=[
    "id"=> $_POST["id"],
    "nombre"=> $_POST["nombre"],
    "direccion"=> $_POST["direccion"],
    "correo"=> $_POST["correo"],
    "telefono"=> $_POST["telefono"]
    
];
try{
    # preparando la consulta
    $sentencia = $conex->prepare("update clientes set nombre=:nombre, direccion=:direccion, correo=:correo, telefono=:telefono where id=:id;");
    $resultado = $sentencia->execute($clientes);
    http_response_code(200);
    echo "datos actualizados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();
}

?>