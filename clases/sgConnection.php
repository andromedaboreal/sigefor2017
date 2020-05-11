<?php

define ("C_ERROR_CNN_FALLIDA","Error: No se pudo hacer la conexión al servidor DB");
define ("C_ERROR_BD_FALLIDA","Error: No se pudo conectar a la base de datos");
define ("C_ERROR_QUERY","Error: No se pudo realizar la consulta");
define ("C_MERROR_DUPLICADO","4");
define ("C_MERROR_RESTRICCION","1");
define ("C_MERROR_GENERAL","100");

define ("C_ERROR_RESTRICCION","Error: fallo en la restricciones de la tabla");
define ("C_ERROR_ELIMINACION","Error: No se pudo hacer la eliminación, existe una restricción en la tabla");
define ("C_ERROR_COLUMNA","Error: columna desconocida en la consulta ejecutada");
define ("C_ERROR_DUPLICADO","Error: El registro que se intentó agregar ya existe");
define ("C_ERROR_TABLA","Error: La tabla no existe");
define ("C_ERROR_GENERAL","Error: Transacción Fallida");
define ("C_ERROR_EXISTE_DB","No se puede crear la Base de Datos, ya existe");
define ("C_ERROR_UPD_DEL_FK","Este registro tiene datos asociados, y no puede ser eliminado o actualizado");
include ("descrip_campo.php");
include ("cls_mysql.php");
include ("cls_mysqli.php");
include ("cls_postgres.php");



function sgConnection($s = false){
	
	if($s == false){
		global $_conn;
		$s = $_conn["default"];
	}
	
	//$type, $server="", $user="", $password="", $dbase="",$port=""
	switch(strtolower(trim($s["type"]))){
	case "mysql":
		return new cls_mysql($s["server"], $s["user"], $s["password"], $s["dbase"], $s["port"], $s["charset"]);
		break;
	case "mysqli":
		return new cls_mysqli($s["server"], $s["user"], $s["password"], $s["dbase"], $s["port"], $s["charset"]);
		break;
	case "postgres":
		return new cls_postgres($s["server"], $s["user"], $s["password"], $s["dbase"], $s["port"], $s["charset"]);
		break;
	}// end switch

}// end function

function connection($cn){
	$type = $cn["type"];
	$server = $cn["server"];
	$user = $cn["user"];
	$password = $cn["password"];
	$dbase = $cn["dbase"];
	$port = $cn["port"];
	$charset = $cn["charset"];
	
	switch(strtolower(trim($type))){
	case "mysql":
		return new cls_mysql($server, $user, $password, $dbase, $port, $charset);
		break;
	case "mysqli":
		return new sgMysql($server, $user, $password, $dbase, $port, $charset);
		break;
	case "postgres":
		return new sgPostgres($server, $user, $password, $dbase, $port, $charset);
		break;	
	}

}

?>