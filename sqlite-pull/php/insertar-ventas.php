<?php
include 'vars.php';

#verificar si vienen losparametros requeridos
if (empty($_POST["fecha"])) {
    http_response_code(400);
	exit("Falta la fecha"); #Terminar el script definitivamente
}

if (empty($_POST["total"])) {
    http_response_code(400);
	exit("falta total"); #Terminar el script definitivamente
}
//
$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ventas=[
    "fecha"=> $_POST["fecha"],
    "total"=> $_POST["total"]
];
try{
    # preparando la consulta
    $sentencia = $conex->prepare("insert into ventas(fecha, total) values(:fecha, :total);");
    $resultado = $sentencia->execute($ventas);
    http_response_code(200);
    echo "datos insertados";

}catch(PDOException $exc){
    http_response_code(400);
    echo "Lo siento, ocurrió un error:".$exc->getMessage();

}

?>