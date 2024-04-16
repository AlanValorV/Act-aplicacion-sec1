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

if (empty($_POST["apellido"])) {
    http_response_code(400);
	exit("insertar apellido"); 
}
if (empty($_POST["puesto"])) {
    http_response_code(400);
	exit("insertar puesto"); 
}
if (empty($_POST["salario"])) {
    http_response_code(400);
	exit("insertar salario"); 
}
if (empty($_POST["fecha"])) {
    http_response_code(400);
	exit("insertar fecha de contratacion"); 
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$empleados=[
    "id"=> $_POST["id"],
    "nombre"=> $_POST["nombre"],
    "apellido"=> $_POST["apellido"],
    "puesto"=> $_POST["puesto"],
    "salario"=> $_POST["salario"],
    "fecha"=> $_POST["fecha"]

];
try{
    # preparando la consulta
    $sentencia = $conex->prepare("update empleados set nombre=:nombre, apellido=:apellido, puesto=:puesto, salario=:salario, fecha=:fecha where id=:id;");
    $resultado = $sentencia->execute($empleados);
    http_response_code(200);
    echo "datos actualizados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();
}

?>