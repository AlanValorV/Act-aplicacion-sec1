<?php
include 'vars.php';

#verificar si vienen losparametros requeridos
if (empty($_POST["nombre"])) {
    http_response_code(400);
	exit("Falta nombre"); #Terminar el script definitivamente
}

if (empty($_POST["apellido"])) {
    http_response_code(400);
	exit("falta apellido"); #Terminar el script definitivamente
}
if (empty($_POST["puesto"])) {
    http_response_code(400);
	exit("falta puesto"); #Terminar el script definitivamente
}
if (empty($_POST["salario"])) {
    http_response_code(400);
	exit("falta el salario"); #Terminar el script definitivamente
}
if (empty($_POST["fecha"])) {
    http_response_code(400);
	exit("falta fecha"); #Terminar el script definitivamente
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$empleados=[
    "nombre"=> $_POST["nombre"],
    "apellido"=> $_POST["apellido"],
    "puesto"=> $_POST["puesto"],
    "salario"=> $_POST["salario"],
    "fecha"=> $_POST["fecha"]
];
try{
    # preparando la cosnaulta
    $sentencia = $conex->prepare("insert into empleados(nombre, apellido, puesto, salario, fecha) values(:nombre, :apellido, :puesto, :salario, :fecha);");
    $resultado = $sentencia->execute($empleados);
    http_response_code(200);
    echo "datos insertados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();

}

?>