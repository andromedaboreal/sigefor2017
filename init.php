<?php

//ini_set("error_reporting","1");
//ini_set('default_charset', 'utf-8');

//echo (ini_get('default_charset'));exit;
$PATH = C_PATH;
$PATH_SEVIAN = C_PATH_SEVIAN;

error_reporting(E_ALL);
ini_set("display_errors", true); 
//ini_set('html_errors', false);
define ("SS_CHARSET", "utf-8");
//define ("SS_CHARSET", "iso-8859-1");
define ("SC_DATE_TIME_ZONE", "America/Caracas");

//define ("C_BD_PORT","3306");
//define ("C_BD_DRIVER","mysqli");
//define ("C_BD_CHARSET",SS_CHARSET);

define ("C_BD_PORT", C_PUERTO);
define ("C_BD_DRIVER", C_DRIVER);
define ("C_BD_CHARSET", SS_CHARSET);

$_conn["default"] = array(
	"type"		=>	C_BD_DRIVER,
	"server"	=>	C_SERVIDOR,
	"user"		=>	C_USUARIO,
	"password"	=>	C_PASSWORD,
	"dbase"		=>	C_BDATOS,
	"port"		=>	C_BD_PORT,
	"charset"	=>	C_BD_CHARSET,
);
$clsInput = $clsElement = array();
$clsElement["201"] = 
$clsElement["query"] = array(
	"file" => "{$PATH}clases/query.php",
	"class" => "query");

$clsElement["202"] = 
$clsElement["reporte"] = array(
	"file" => "{$PATH}clases/cls_reporte.php",
	"class" => "cls_reporte");

$clsInput["300"] = array(
	"file" => "sgSelectText.php",	
	"css" => "",	
	"js" => "",	
	"class" => "sgSelectText", 
	"type"  => "");



//$cssFiles = array();
//$jsFiles = array();

$cssFiles = array(
	"{$PATH_SEVIAN}css/Menu.css",
	"{$PATH_SEVIAN}css/sgWindow.css",
	"{$PATH_SEVIAN}css/sgCalendar.css",
	"{$PATH_SEVIAN}css/selectText.css",
	"{$PATH_SEVIAN}css/sgTab.css",
	"{$PATH_SEVIAN}css/sgAjax.css",
	"{$PATH_SEVIAN}css/grid.css",
	"{$PATH}css/query.css",
	"{$PATH}css/debug.css",
);

$jsFiles[] = array("{$PATH_SEVIAN}_js/_sgQuery.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/sgAjax.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/drag.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/sgWindow.js", true);
//$jsFiles[] = array("{$PATH_SEVIAN}js/sgDB.js", true);
//$jsFiles[] = array("{$PATH_SEVIAN}js/sgMenu.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/Sevian/Menu.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/sgCalendar.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/selectText.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/sgTab.js", true);
$jsFiles[] = array("{$PATH_SEVIAN}js/sgGrid.js", true);
$jsFiles[] = array("{$PATH}js/sgTipsPopup.js", true);
$jsFiles[] = array("{$PATH}js/datePicker.js", true);
$jsFiles[] = array("{$PATH}js/sgInput.js", true);
$jsFiles[] = array("{$PATH}js/sgPanel.js", true);

$jsFiles[] = array("{$PATH}jscript/funciones.js", false);
$jsFiles[] = array("{$PATH}jscript/cls_calendario.js", false);
$jsFiles[] = array("{$PATH}jscript/ffunciones.js", false);
$jsFiles[] = array("{$PATH}jscript/cls_capa.js", false);
$jsFiles[] = array("{$PATH}jscript/cls_menu.js", false);

$jsFiles[] = array("{$PATH}jscript/funciones_nuevas.js", true);
$jsFiles[] = array("{$PATH}jscript/cls_validacion.js", true);
$jsFiles[] = array("{$PATH}jscript/elementos.js", true);
$jsFiles[] = array("{$PATH}jscript/multiple.js", true);
$jsFiles[] = array("{$PATH}jscript/data_check.js", true);
$jsFiles[] = array("{$PATH}jscript/formulario.js", true);
$jsFiles[] = array("{$PATH}jscript/lista_set.js", true);
$jsFiles[] = array("{$PATH}jscript/cesta2.js", true);
$jsFiles[] = array("{$PATH}jscript/cls_grid.js", true);
$jsFiles[] = array("{$PATH}jscript/multiple.js", true);
$jsFiles[] = array("{$PATH}jscript/layer.js", true);
$jsFiles[] = array("{$PATH}jscript/funciones_extras.js", true);


?>