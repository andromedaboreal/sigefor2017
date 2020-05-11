<?php
include("init.php");

ini_set('default_charset', SS_CHARSET);


define ("SS_PATH_QUERY", "SS_PATH_QUERY");
define ("SS_INS", "SS_INS");
define ("SS_PATH","SS_PATH");

date_default_timezone_set(SC_DATE_TIME_ZONE);

include (C_PATH_CONFIGURACION."configuracion.php");
include ("clases/sg_configuracion.php");
include ("clases/funciones.php");
include ("clases/funciones_sg.php");
include ("clases/sgConnection.php");

include ("clases/cls_documento.php");

include ("clases/cfg_estructura.php");
include ("clases/cls_menu.php");
include ("clases/cfg_procedimiento.php");
include ("clases/cfg_comando.php");

include ("clases/cls_panel.php");
require ("clases/cls_navegador.php");
include ("clases/cfg_accion.php");
include ("clases/cfg_secuencia.php");

include ("clases/cfg_modulo.php");

include("clases/../../sevian/class2/sg_html.php");
include ("debug.php");

session_start();
class sgPanel{
	
	
	
	public function __construct(){
		

		$ini = true;
		$ins = 	&$_SESSION["INS"];

		//********************
		if(isset($_REQUEST["cfg_ins_aux"]) and $_REQUEST["cfg_ins_aux"]){
			$ins = $_REQUEST["cfg_ins_aux"];
			$ini = false;
		}else{
			do{
				$ins = date("Ymdhis").str_pad(rand(1,999),3,"0",STR_PAD_LEFT);
			}while(isset($_SESSION["VSES"][$ins]["SS_INS"]));
		}// end if
		//********************

		
		$this->ini = $ini;
		$this->ins = $ins;
		$this->sesion = &$_SESSION;
		$this->vform = &$_REQUEST;
		$this->server = &$_SERVER;
		$this->modulo = C_MODULO_PRINCIPAL;
		
		
		
		
		
	}
	
	
	public function crear_var_session(){
		
		
		
		if(!isset($this->vform["cfg_tgt_aux"]) and ($this->ini or $this->vform["cfg_est_aux"] == "")){
		
			unset($this->sesion["VSES"][$this->ins]);
			$this->sesion["VSES"][$this->ins] = array();
		}// end if
		$this->vses = &$this->sesion["VSES"][$this->ins];
		
		if(isset($this->vform["cfg_tgt_aux"]) and $this->vform["cfg_tgt_aux"]){
			
			
			
			$this->tgt = $this->vform["cfg_tgt_aux"];
			
			
			
		}else{
			$this->tgt = 1;
		}// end if
		
		if(!isset($this->vses["DEBUG"])){
			$this->vses["DEBUG"] = "0";
			
		}
		
		$this->ses = &$this->sesion["SES"][$this->ins][$this->tgt];
		
		//date_default_timezone_set("America/Caracas");

		if(!isset($this->vses["SS_FECHA_ACTUAL"])){
			$this->vses["SS_FECHA_ACTUAL"] = date("d/m/Y");
			$this->vses["SS_FECHA_SQL"] = date("Y-m-d");
			
			//********************
			$hoy = getdate();
			$this->vses["SS_FECHA_MES"] = $hoy["mon"];
			$this->vses["SS_FECHA_ANO"] = $hoy["year"];
			$this->vses["SS_FECHA_DIA"] = $hoy["mday"];
			$this->vses["SS_FECHA_CMES"] = c_mes($hoy["mon"]);
			$this->vses["SS_PATH"] = $this->path;
			$this->vses["SS_PATH_QUERY"] = $this->path."query.php";
			$this->vses["SS_BDATOS"] = C_BDATOS;
			$this->vses["SS_SERVER_ADDR"] = $this->server["SERVER_ADDR"];
			$this->vses["SS_REMOTE_ADDR"] = $this->server["REMOTE_ADDR"];
			$ruta = pathinfo($this->server["PHP_SELF"]);
			$this->vses["SS_DOCUMENT_ROOT"] = $ruta['dirname'];
			$this->vses["SS_INS"] = $this->ins;
			
			if(defined("C_IP")){
				$this->vses["SS_IP"] = C_IP;
			}
			
			$this->vses["SS_PATH"] = C_PATH;
			$this->vses["SS_PATH_IMAGENES"] = C_PATH_IMAGENES;
			$this->vses["SS_PATH_CSS"] = C_PATH_CSS;
			$this->vses["SS_PATH_ARCHIVOS"] = C_PATH_ARCHIVOS;
			$this->vses["SS_CLASE_DEFAULT"] = C_CLASE_DEFAULT;
			$this->vses["SS_TEMA_DEFAULT"] = C_TEMA_DEFAULT;
			$this->vses["SS_MODULO"] = C_MODULO_PRINCIPAL;
			$this->vses["SS_BD_USUARIO"] = C_USUARIO;
			$this->vses["SS_BD_PASSWORD"] = C_PASSWORD;
			$this->vses["SS_BD_SERVIDOR"] = C_SERVIDOR;
			$this->vses["SS_PATH_REPORTES"] = C_PATH_REPORTES; 
		}//end if
		
		$this->vses["SS_HORA_ACTUAL"] = date("h:i A");
		
		$this->vses["SS_HORA_SQL"] = date("H:i:s");
		
	}// end funtion
	
	
	public function render(){
		$this->crear_var_session();
		
		$f = new stdClass;
		$f->cedula = $_POST["cedula"];
		$f->targetId = "sg_panel_4";
		$f->html = "<b class='z'>Tres</b>  Chao";
		$f->script = "";
		$f->css = ".z{font-weight:bold;color:orange;}";
		$f->typeAppend = 0;

		return json_encode($f); 
		
	}
	
	
}

$s = new sgPanel();
header('Content-Type: text/html; charset='.SS_CHARSET);
echo $s->render();




?>