<?php
include 'vars.php';

#verificar si vienen losparametros requeridos
if (empty($_POST["nombre"])) {
    http_response_code(400);
	exit("Falta nombre"); #Terminar el script definitivamente
}

if (empty($_POST["direccion"])) {
    http_response_code(400);
	exit("falta direccion"); #Terminar el script definitivamente
}
if (empty($_POST["correo"])) {
    http_response_code(400);
	exit("falta correo"); #Terminar el script definitivamente
}
if (empty($_POST["telefono"])) {
    http_response_code(400);
	exit("falta telefono"); #Terminar el script definitivamente
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$clientes=[
    "nombre"=> $_POST["nombre"],
    "direccion"=> $_POST["direccion"],
    "correo"=> $_POST["correo"],
    "telefono"=> $_POST["telefono"]
];
try{
    # preparando la cosnaulta
    $sentencia = $conex->prepare("insert into clientes(nombre, direccion, correo, telefono) values(:nombre, :direccion, :correo, :telefono);");
    $resultado = $sentencia->execute($clientes);
    http_response_code(200);
    echo "datos insertados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();

}

?>