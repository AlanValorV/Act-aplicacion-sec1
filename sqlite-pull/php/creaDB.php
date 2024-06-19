<?php
/**
 * Abre una base de datos de SQLite
 * @return object apuntador al manejadro de la BD
 */
function abrirDB()
{
    $archivo="./baseD.sqlite3";
    if(file_exists($archivo)){
        echo "la base de datos ya existe";
        return null;
    }else{
        $baseDeDatos = new PDO("sqlite:" . $archivo);
        $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $baseDeDatos;
    }
}

/**
 * crea la tabla partes si no existe
 * @param object $baseDeDatos manejador de base de datos de sqlite
 */
function crearTablaProductos($baseDeDatos)
{
    $definicionTabla = "create table if not exists productos(
    id integer primary key autoincrement,
        nombre varchar(50),
        descripcion varchar(50),
        precio float,
        stock integer
  
   );";

    $resultado = $baseDeDatos->exec($definicionTabla);
    return $resultado;
}

function crearTablaClientes($baseDeDatos)
{
    $definicionTabla = "create table if not exists clientes(
    id integer primary key autoincrement,
        nombre varchar(50),
        direccion varchar(50),
        correo varchar(50),
        telefono int(10),
   );";

    $resultado = $baseDeDatos->exec($definicionTabla);
    return $resultado;
}

function crearTablaEmpleados($baseDeDatos)
{
    $definicionTabla = "create table if not exists empleados(
    id integer primary key autoincrement,
        nombre varchar(50),
        apellido varchar(50),
        puesto varchar(50),
        salario float,
        Fecha date,
   );";

    $resultado = $baseDeDatos->exec($definicionTabla);
    return $resultado;
}

function crearTablaVentas($baseDeDatos)
{
    $definicionTabla = "create table if not exists ventas(
    id integer primary key autoincrement,
        fecha date,
        total float
   );";

    $resultado = $baseDeDatos->exec($definicionTabla);
    return $resultado;
}
/**
 * Inserta un datos recibe un arreglo de esta forma:
 * $datosParte=[
 *	"nombre" => "",
 *	"alias" => ""
 *];
 *@param array $tipoParte array
 *@param object $baseDeDatos apuntador al manejador de base de datos
 *@return boolean sucess o fail
 */
function insertaProductos($baseDeDatos, $productos)
{
    $query="insert into productos(nombre, descripcion, precio, stock) VALUES(:nombre, :descripcion, :precio, :stock);";
    $sentencia = $baseDeDatos->prepare($query);
    $resultado = $sentencia->execute($productos);
    if ($resultado === true) {
        http_response_code(200);
        return true;
    } else {
        http_response_code(400);
        return false;
    }
}

function insertaClientes($baseDeDatos, $clientes)
{
    $query="insert into clientes(nombre, direccion, correo, telefono) VALUES(:nombre, :direccion, :correo, :telefono);";
    $sentencia = $baseDeDatos->prepare($query);
    $resultado = $sentencia->execute($clientes);
    if ($resultado === true) {
        http_response_code(200);
        return true;
    } else {
        http_response_code(400);
        return false;
    }
}

function insertaEmpleados($baseDeDatos, $empleados)
{
    $query="insert into empleados(nombre, apellido, puesto, salario, fecha) VALUES(:nombre, :apellido, :puesto, :salario, :fecha);";
    $sentencia = $baseDeDatos->prepare($query);
    $resultado = $sentencia->execute($empleados);
    if ($resultado === true) {
        http_response_code(200);
        return true;
    } else {
        http_response_code(400);
        return false;
    }
}

function insertaVentas($baseDeDatos, $ventas)
{
    $query="insert into ventas(fecha, total) VALUES(:fecha, :total);";
    $sentencia = $baseDeDatos->prepare($query);
    $resultado = $sentencia->execute($ventas);
    if ($resultado === true) {
        http_response_code(200);
        return true;
    } else {
        http_response_code(400);
        return false;
    }
}
/**
 * Inserta un conjunto de registros de ejemplo
 * @param object $baseDeDatos manejador de la bd 
 * @param array $DatosPartes arreglo asociativo con la lista de datos a insertar
 */
function insertaDatosProductos($baseDeDatos, $DatosProductos)
{
    //insertar datos de ejeplo
    $productos = [
        "nombre" => "",
        "descripcion" => "",
        "precio" => "",
        "stock" => ""
    ];
    foreach ($DatosProductos as $valor) {
        $productos["nombre"] = $valor["nombre"];
        $productos["descripcion"] = $valor["descripcion"];
        $productos["precio"] = $valor["precio"];
        $productos["stock"] = $valor["stock"];
        insertaProductos($baseDeDatos, $productos);
    }
}

function insertaDatosClientes($baseDeDatos, $DatosClientes)
{
    //insertar datos de ejemplo
    $clientes = [
        "nombre" => "",
        "direccion" => "",
        "correo" => "",
        "telefono" => ""
    ];
    foreach ($DatosClientes as $valor) {
        $clientes["nombre"] = $valor["nombre"];
        $clientes["direccion"] = $valor["direccion"];
        $clientes["correo"] = $valor["correo"];
        $clientes["telefono"] = $valor["telefono"];
        insertaClientes($baseDeDatos, $clientes);
    }
}

function insertaDatosEmpleados($baseDeDatos, $DatosEmpleados)
{
    //insertar datos de ejemplo
    $empleados = [
        "nombre" => "",
        "apellido" => "",
        "puesto" => "",
        "salario" => "",
        "fecha" => ""
    ];
    foreach ($DatosEmpleados as $valor) {
        $empleados["nombre"] = $valor["nombre"];
        $empleados["apellido"] = $valor["apellido"];
        $empleados["puesto"] = $valor["puesto"];
        $empleados["salario"] = $valor["salario"];
        $empleados["fecha"] = $valor["fecha"];
        insertaEmpleados($baseDeDatos, $empleados);
    }
}

function insertaDatosVentas($baseDeDatos, $DatosVentas)
{
    //insertar datos de ejemplo
    $ventas = [
        "fecha" => "",
        "total" => ""
    ];
    foreach ($DatosVentas as $valor) {
        $ventas["fecha"] = $valor["fecha"];
        $ventas["total"] = $valor["total"];
        insertaVentas($baseDeDatos, $ventas);
    }
}

$db = abrirDB();
if ($db) {
    try{
        crearTablaProductos($db);
        insertaDatosProductos($db, $DatosProductos);
        crearTablaClientes($db);
        insertaDatosClientes($db, $DatosClientes);
        crearTablaEmpleados($db);
        insertaDatosEmpleados($db, $DatosEmpleados);
        crearTablaVentas($db);
        insertaDatosVentas($db, $DatosVentas);
        http_response_code(200);
        echo "ok";
    }catch(Exception $Exception){
        http_response_code(400);
        echo "Error: " . $Exception;
    }
} else {
    http_response_code(400);
    echo "la base de datos ya existe";
}

?>