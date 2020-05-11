<?php
include("init.php");
//echo SS_CHARSET;
ini_set('default_charset', "utf-8");
//echo ini_get('default_charset');phpinfo();exit;
$tiempo_inicio = microtime(true);
$debug = "";


define ("SS_PATH_QUERY", "SS_PATH_QUERY");
define ("SS_INS", "SS_INS");
define ("SS_PATH","SS_PATH");

date_default_timezone_set(SC_DATE_TIME_ZONE);
//header('Content-Type: text/html; charset=iso-8859-1');

include (C_PATH_CONFIGURACION."configuracion.php");
include ("clases/sg_configuracion.php");
include ("clases/funciones.php");
include ("clases/funciones_sg.php");
include ("clases/sgConnection.php");

include ("clases/cls_documento.php");

include ("clases/cfg_estructura.php");

include ("clases/cfg_item.php");
include ("clases/cls_table.php");
include ("clases/cls_element_html.php");
include ("clases/cls_articulo.php");
include ("clases/cls_menu.php");
include ("clases/cfg_procedimiento.php");
include ("clases/cfg_comando.php");

include ("clases/cls_panel.php");
require ("clases/cls_navegador.php");
include ("clases/cfg_accion.php");
include ("clases/cfg_secuencia.php");

include("clases/cls_control.php");
include("clases/cfg_formulario.php");

include ("clases/cfg_modulo.php");

include("clases/../../sevian/class2/sg_html.php");

include_once("clases/../../sevian/class2/sgCmd.php");
include_once("clases/../../sevian/class2/sgDBase.php");
include_once("clases/../../sevian/class2/sgMysql.php");
include_once("clases/../../sevian/class2/sgPostgres.php");

include ("debug.php");
session_start();
//===========================================================
class cls_sigefor extends cls_documento{
	public $panel = false;
	var $panel_default = C_PANEL_DEFAULT;
	var $last_panel = 0;
	
	var $debug = 0;

	var $estructura = C_EST_DEFAULT;
	var $elemento_script = C_ELE_SCRIPT;

	var $usuario = C_SG_USUARIO;
	var $clave = C_SG_CLAVE;
	var $est = "";

	var $usuario_def = C_SG_USUARIO;
	var $clave_def = C_SG_CLAVE;
	var $est_def = C_EST_DEFAULT;
	var $ini_est = false;
	
	var $aut = false;
	var $cambio_est = false;

	var $con_interaccion = false;

	var $clase = C_CLASE_DEFAULT;
	var $umodulo = "";
	var $modulo = C_MODULO_PRINCIPAL;


	var $metodo = C_METODO;	
	var $path = C_PATH;
	var $path_imagenes = C_PATH_IMAGENES;
	var $path_css = C_PATH_CSS;
	var $path_archivos = C_PATH_ARCHIVOS;
	
	private $tgt = false;
	
	var $paneles = array();
	//********************
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	var $vst = array();
	
	public $elem = array();
	public $param = false;
	
	public $async = false;
	
	public $css_files = array();
	public $js_files = array();
	
	//===========================================================
	public function __construct(){
		
		$ini = true;
		$ins = 	&$_SESSION["INS"];

		//********************
		if(isset($_REQUEST["cfg_ins_aux"]) and $_REQUEST["cfg_ins_aux"]){
			$ins = $_REQUEST["cfg_ins_aux"];
			$ini = false;
		}else{
			do{
				$ins = date("Ymdhis").str_pad(rand(1,999),3,"0", STR_PAD_LEFT);
			}while(isset($_SESSION["VSES"][$ins]["SS_INS"]));
		}// end if		
		
		
		
		$this->ini = $ini;
		$this->ins = $ins;
		$this->sesion = &$_SESSION;
		$this->vform = &$_REQUEST;
		$this->server = &$_SERVER;
		
		
		
		
		


	}// end function
	//===========================================================
	function eval_metodo(){
		if($this->ini){
			return;
		}// end if
		if($this->metodo != $this->server["REQUEST_METHOD"] and $this->server["argc"] > 0){
			header("Location: ".$this->server["PHP_SELF"]);
			exit;
		}// end if
	}// end if
	//===========================================================
	function crear_var_session(){
		
		
		
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
	//===========================================================
	function extraer_paneles($diagrama_x){
		$exp = "|~~([0-9]+)~~|";
		if(preg_match_all($exp, $diagrama_x, $c)){
			foreach($c[1] as $a => $b){
				$this->paneles[trim($b)]="1";
			}// next
		}// next
	}// end function
	//===========================================================
	function extraer_param(){
		if(isset($this->vform["cfg_param_aux"]) and $this->vform["cfg_param_aux"] != ""){
			if($this->param = json_decode($this->vform["cfg_param_aux"], true)){
				//print_r($this->param);exit;
			}else{
				$this->param = extraer_para($this->vform["cfg_param_aux"]);
			}

			
			if(isset($this->param["elemento"]) and $this->param["elemento"] == "accion"){
				$this->eval_accion($this->param["nombre"]);
			}// end if

			$this->var_elem($this->param);
		}// end if
	}// end function
	//===========================================================
	function var_elem($param_x){
		//********************
		if(isset($param_x["panel"]) and ($param_x["panel"] == "0" or $param_x["panel"] == "")){
			$panel = $this->panel_default;
		}else if(isset($param_x["panel"]) and $param_x["panel"] < 0){
			$panel = $this->last_panel;
		}else if(isset($param_x["panel"])){
			$panel = $param_x["panel"];	
		}else{
			$panel = $this->panel_default;
		}// end if
		//********************
		
		
		if(!isset($this->elem[$panel])){
			$this->elem[$panel] = new cls_panel;
			$this->elem[$panel]->dinamico = true;
		}

		$elem = &$this->elem[$panel];
		
		if($panel != ""){
			$elem->panel = $panel;
		}// end if
		//********************
		if(isset($param_x["elemento"]) and $param_x["elemento"]){
			$elem->elemento = $param_x["elemento"];
		}// end if
		//********************
		if(isset($param_x["nombre"]) and $param_x["nombre"] != ""){
			$elem->nombre = $param_x["nombre"];
		}else if($elem->elemento == C_OBJ_FORMULARIO){
			$elem->nombre = $elem->formulario;
		}else if($elem->elemento == C_OBJ_CONSULTA){
			$elem->nombre = $elem->vista;
		}// end if
		//********************
		if(isset($param_x["modo"]) and $param_x["modo"] != ""){
			$elem->modo = $param_x["modo"];
		}// end if

		if(isset($param_x["registro"]) and $param_x["registro"] != ""){
			$elem->reg = $param_x["registro"];
		}else if($this->vform["cfg_reg_aux"] != ""){
			$elem->reg = &$this->vform["cfg_reg_aux"];
		}// end if
		//$elem->registro = $this->vform["cfg_registro_aux"];
		
		if(isset($param_x["pagina"]) and $param_x["pagina"] != ""){
			$elem->pagina = $param_x["pagina"];
		}// end if
		if(isset($param_x["referencia"]) and $param_x["referencia"] != ""){
			$elem->referencia = $param_x["referencia"];
		}// end if
		if(isset($param_x["target"]) and $param_x["target"] != ""){
			$elem->target = $param_x["target"];
		}// end if
		if(isset($param_x["estructura"]) and $param_x["estructura"] != ""){
			$this->est = $param_x["estructura"];
		}// end if
		if(isset($param_x["de_sesion"]) and $param_x["de_sesion"] != ""){
			$elem->de_sesion = $param_x["de_sesion"];
		}// end if
		if(isset($param_x["de_vsesion"]) and $param_x["de_vsesion"] != ""){
			$elem->de_vsesion = $param_x["de_vsesion"];
		}// end if
		if(isset($param_x["volver"]) and $param_x["volver"] != ""){
			$elem->volver = $param_x["volver"];
			
		}// end if


		if(isset($param_x["expresiones"]) and $param_x["expresiones"] != ""){
			$aux = explode(",",$param_x["expresiones"]);
			foreach($aux as $k => $v){
				$aux2 = explode("=",$v);
				$this->vexp[$aux2[0]]=$aux2[1];
			}// next
			$elem->expresiones = $param_x["expresiones"];
		}// end if

		if(isset($param_x["variables"]) and $param_x["variables"] != ""){
			$aux = explode(",",$param_x["variables"]);
			foreach($aux as $k => $v){
				$aux2 = explode("=",$v);
				$this->vses[$aux2[0]]=$aux2[1];
			}// next
			$elem->variables = $param_x["variables"];
		}// end if

		$elem->formulario = $this->vform["cfg_formulario_aux"];
		$elem->vista = $this->vform["cfg_vista_aux"];
		/*
		if($param_x["leer_config"] != ""){
			$elem->formulario = $this->vform["cfg_formulario_aux"];
			$elem->vista = $this->vform["cfg_vista_aux"];
		}// end if
		*/
		//$elem->vista = $this->vform["cfg_vista_aux"];
		//$elem->formulario = $this->vform["cfg_formulario_aux"];

		//********************
		//$elem->sw = $this->sw;
		//$elem->sw2 = $this->sw;
		//********************
		$elem->actualizado = true;
	}// end function
	//===========================================================
	function eval_mod($mod_x=""){
		if(!isset($this->ses["MOD"]) or $this->ses["MOD"] == "" or $this->ses["MOD"] != $mod_x  or $this->ini){
			$mod = new cfg_modulo();
			$mod->vform = &$this->vform;
			$mod->vses = &$this->vses;
			$mod->vexp = &$this->vexp;

			$this->ses["CSS"] = array();
			$this->ses["JS"] = array();


			if($mod->ejecutar($mod_x)){

				$this->vses["DEBUG"] = $mod->debug;
				$this->ses["MOD"] = $mod_x;
				
				if(isset($mod->cfg->css)){
					$css_x = explode(C_SEP_L,$mod->cfg->css);
					foreach ($css_x as $k => $v){
						$this->ses["CSS"][] = $v;
					}// next
				}// end if
				if(isset($mod->cfg->js)){
					$css_x = explode(C_SEP_L,$mod->cfg->js);
					foreach ($css_x as $k => $v){
						$this->ses["JS"][] = $v;
					}// next
				}// end if
				if(isset($mod->cfg->variables)){
					$aux = explode(",",$mod->cfg->variables);
					foreach($aux as $k => $v){
						$aux2 = explode("=",$v);
						$this->vses[$aux2[0]]=$aux2[1];
					}// next

				}// end if
				if($mod->login != ""){
					$this->usuario_def = $mod->login;
					$this->clave_def = $mod->password;
				}// end if
				
				$this->est_def = $mod->estructura;
				
				
				
				return $mod->estructura;		
			}// end if
		}//end if
		return false;
	}// end function
	//===========================================================
	function eval_proc($proc_x=""){
		if($proc_x == ""){
			return false;
		}// end if
		$proc = new cfg_procedimiento;
		$proc->act = $this->act;
		$proc->vses = &$this->vses;
		$proc->vform = &$this->vform;
		$proc->vexp = &$this->vexp;		
		$proc->deb = &$this->deb;		
		return $proc->ejecutar($proc_x);
	}// end function
	//===========================================================
	function eval_cmd($cmd_x=""){
		if($cmd_x == ""){
			return "";
		}// end if
		$cmd = new cfg_comando();
		$cmd->vses = &$this->vses;
		$cmd->vform = &$this->vform;
		$cmd->vexp = &$this->vexp;
		$cmd->deb = &$this->deb;		
		$cmd->ejecutar($cmd_x);

		if($cmd->mensaje){
			$this->msgbox($cmd->mensaje);
		
		}// end if
		$acciones = explode(C_SEP_Q,$cmd->acciones);
		
		foreach($acciones as $k => $v){
			
			if($accion = $this->eval_accion($v)){
				$this->secuencia($accion);
				$this->var_elem($this->param);
				
			}// end if
		}// next
	}// end function
	//===========================================================
	function eval_objeto($obj_x="", $func_x=""){
		require_once("clases/cfg_objeto.php");
		$ele = new cfg_objeto();
		if($aux = $ele->ejecutar($obj_x, $func_x)){
			foreach($aux as $k => $v){
				if($accion = $this->eval_accion($v)){
					$this->secuencia($accion);
					$this->var_elem($this->param);
				}// end if
			}// next
		}// end if
	}// end function
	//===========================================================
	function autorizar($usuario_x,$clave_x,$est_x){
		require_once("clases/cfg_autentificacion.php");
		$aut = new cfg_autentificacion;
		$aut->usuario = $usuario_x;
		$aut->clave = $clave_x;
		$aut->est = $est_x;
		$aut->intentos = "2";
		
		if(!$aut->ejecutar()){
			$this->msgbox($aut->msg_error);
			$this->est = $this->ses["EST"];
			$this->vses["SS_ERROR_LOGIN"] = "1";	
		}else{
			$this->aut = true;
			$this->usuario = $aut->usuario;
			$this->clave = $aut->clave;
			$this->est = $aut->est;
			$this->ses["USUARIO"] = $this->usuario;
			$this->ses["CLAVE"] = $this->clave;
			$this->ses["EST"] = $this->est;
			$this->ses["ELEM"] = $this->elem;
			$this->ses["AUT"] = $this->aut;			

			$this->vses["SS_USUARIO"] = $this->usuario;
			$this->vses["SS_CLAVE"] = $this->clave;
			$this->vses["SS_EST"] = $this->est;
			$this->vses["SS_ELEM"] = $this->elem;
			$this->vses["SS_AUT"] = &$this->aut;			

			$this->vses["SS_ERROR_LOGIN"] = "0";	
			$this->cambio_est = true;
		}// end if
		$this->vses["SS_GRUPO"] = $aut->grupo;
	}// end if
	//===========================================================
	function eval_sec($sec_x){
		$sec = new cfg_secuencia();
		$sec->vses = &$this->vses;
		$sec->vform = &$this->vform;
		$sec->vexp = &$this->vexp;
		$sec->deb = &$this->deb;		

		if(!$sec->ejecutar($sec_x)){
			return false;
		}// end if
		$procesos = extraer_sec($sec->parametros);

		foreach($procesos[0] as $k => $proceso){
		
			$valor = stripslashes($procesos[1][$k]);
					
			switch($proceso){
			case "validar":
				//validar();
				break;
			case "comando":
				$this->eval_cmd($valor);
				break;
			case "procedimiento":
				$this->eval_proc($valor);
				break;
			case "guardar":
				$this->guardar();
				break;
			case "aplicacion":
				//aplicacion();
				break;
			case "vses":
				$aux2 = explode("=",$valor);
				$this->vses[$aux2[0]] = $aux2[1];
				break;
			case "vform":
				$aux2 = explode("=",$valor);
				$this->vform[$aux2[0]] = $aux2[1];
				break;
			case "vexp":
				$aux2 = explode("=",$valor);
				$this->vexp[$aux2[0]] = $aux2[1];
				break;
			case "guardar_form":
				$this->vses[$valor] = $this->vform;
				break;
			case "eliminar_form":
				unset($this->vses[$valor]);
				break;
			case "eliminar_vses":
				unset($this->vses[$valor]);
				break;
			case "eliminar_vform":
				unset($this->vform[$valor]);
				break;
			case "eliminar_vexp":
				unset($this->vexp[$valor]);
				break;
			case "autorizar":
				$this->autorizar($this->vform["usuario"],$this->vform["clave"],$this->vform["estructura"]);
				break;
			case "cerrar_sesion":
				$this->ses["USUARIO"]=$this->usuario_def; 
				$this->ses["CLAVE"]=$this->clave_def;
				break;
			case "desautorizar":
				$this->autorizar($this->usuario_act,$this->clave_act,$this->est_act);
				break;
			case "cambiar_clave":
				//$this->ses["USUARIO"]=$this->usuario_def; 
				$this->ses["CLAVE"]=$this->vses["SS_CLAVE"];
				break;
			case "ini_est":
				$this->ini_est = true;
				break;
			case "salir":
				$this->salir();
				break;
			case "":
				//uno();
				break;
			}// end switch
		}// next
	}// end function
	//===========================================================
	function guardar(){
		if(!$this->act){
			$this->con_interaccion = false;
			return false;
		}// end if
		require_once("clases/cfg_formulario.php");
		require_once("clases/cfg_campo.php");
		require_once("clases/cfg_actualizar.php");
		$act = new cfg_actualizar;
		$act->vses = &$this->vses;
		$act->vform = &$this->vform;
		$act->vexp = &$this->vexp;
		$act->deb = &$this->deb;
		

		$act->data[0] = $this->vform;
		
		
		
		
		$act->formulario = $this->vform["cfg_formulario_aux"];
		$act->interaccion = $this->param["interaccion"];
		
		

		if($act->interaccion < 100){
			$modo = $act->interaccion;
		}else{
			$modo = false;//c_modo($data[C_MODO]);
			
		}// end if
		if($modo == C_MODO_DELETE and $this->vform["cfg_reg_aux"] != ""){
			$aux = explode(C_SEP_Q,$this->vform["cfg_reg_aux"]);
			foreach($aux as $k => $v){
				if($k > 0){
					$act->data[$k]=$act->data[0];
				}// end if
				$act->data[$k]["cfg_registro_aux"] = $v;
			}// next
		}// end if		
		
		
		$act->ejecutar();	
		//$this->vform["cfg_reg_aux"] = $act->registro;
		
		$this->con_interaccion = true;

		if($act->msg){
			$this->msgbox($act->msg);
		}// end if
	}// end function
	//===========================================================
	function secuencia($acc_x=false){
		if(isset($acc_x["proc_ini"]) and $acc_x["proc_ini"]){
			$this->eval_proc($acc_x["proc_ini"]);
		}// end if
		if(isset($acc_x["cmd_ini"]) and $acc_x["cmd_ini"]){
			$this->eval_cmd($acc_x["cmd_ini"]);
		}// end if
		if(isset($acc_x["secuencia"]) and $acc_x["secuencia"]){
			$this->eval_sec($acc_x["secuencia"]);
		}// end if
		if(isset($acc_x["cerrar_sesion"]) and $acc_x["cerrar_sesion"]){
			$this->ses["USUARIO"]=$this->usuario_def; 
			$this->ses["CLAVE"]=$this->clave_def;
		}// end if
		if(isset($acc_x["autorizar"]) and $acc_x["autorizar"]){
			$this->autorizar($this->vform["usuario"],$this->vform["clave"],$this->vform["estructura"]);
		}// end if
		if(isset($acc_x["desautorizar"]) and $acc_x["desautorizar"]){
			$this->autorizar($this->usuario_act,$this->clave_act,$this->est_act);
		}// end if
		if(isset($acc_x["cambiar_clave"]) and $acc_x["cambiar_clave"]){
			//hr($this->vses["SS_CLAVE"]);
				//$this->ses["USUARIO"]=$this->usuario_def; 
				$this->ses["CLAVE"]=$this->vses["SS_CLAVE"];
				
		}// end if
		if(isset($acc_x["interaccion"]) and $acc_x["interaccion"]){
			$this->guardar();
		}// end if
		if(isset($acc_x["proc_fin"]) and $acc_x["proc_fin"]){
			$this->eval_proc($acc_x["proc_fin"]);
		}// end if
		if(isset($acc_x["cmd_fin"]) and $acc_x["cmd_fin"]){
			$this->eval_cmd($acc_x["cmd_fin"]);
		}// end if
		if(isset($acc_x["nodo"]) and $acc_x["nodo"]){
			$this->eval_nodo($acc_x["nodo"]);
		}// end if
		if(isset($acc_x["objeto"]) and $acc_x["objeto"]){
			$this->eval_objeto($acc_x["objeto"], $acc_x["funcion"]);
		}// end if
		if(isset($acc_x["ini_est"]) and $acc_x["ini_est"]){
			$this->ini_est = true;
		}// end if
		if(isset($acc_x["salir"]) and $acc_x["salir"]){
			$this->salir();
		}// end if
		
		
		
		
		if(isset($acc_x["guardar_form"]) and $acc_x["guardar_form"]){
			$this->vses[$acc_x["guardar_form"]] = $this->vform;
		}// end if
		if(isset($acc_x["de_sesion"]) and $acc_x["de_sesion"]){
			$this->vses["VSFORM"] = $this->vform;
		}// end if
		
	}// end function
	//===========================================================
	function eval_nodo($nodo_x){
		require_once("clases/cfg_nodo.php");
		$nodo = new cfg_nodo();
		$nodo->flujo = $this->ses["NODO_FLUJO"];
		$nodo->orden = $this->ses["NODO_ORDEN"];
		$nodo->vses = &$this->vses;
		$nodo->vform = &$this->vform;
		$nodo->vexp = &$this->vexp;
		if($nodo->ejecutar($nodo_x)){
			$this->ses["NODO_FLUJO"] = $nodo->flujo;
			$this->ses["NODO_ORDEN"] = $nodo->orden;
			$this->eval_objeto($nodo->objeto, $nodo->funcion);
		}// end if
	}// end function
	//===========================================================
	function salir(){
		$this->elem = array();
		$this->autorizar($this->usuario_def,$this->clave_def,@$this->ses["EST_DEF"]);
	}// end function
	//===========================================================
	function eval_accion($accion_x){
	
		if($accion_x != ""){
			$param = new cfg_accion;
			$param->param = &$this->param;
			$param->vses = &$this->vses;
			$param->vform = &$this->vform;
			$param->vexp = &$this->vexp;
			$param->ejecutar($accion_x);
			if($param->param["mensaje"]){
				$this->msgbox($param->param["mensaje"]);
			
			}// end if
			
			return $param->param;
		}// end if
		return false;
	}// end function
	//===========================================================
	function crear_diagrama(){
		
		$path = $this->path;
		
		foreach($this->css_files as $css){
			$this->style($css); 
		} 
		
		foreach($this->js_files as $js){
			if($js[1]){
				$this->js_post($js[0]);
			}else{
				$this->js($js[0]);
			}
		} 
		
		
		if(defined('C_HOJA_CSS')){
			if(is_array(C_HOJA_CSS)){
				foreach(C_HOJA_CSS as $css){
					$this->style($css); 
				}
			}else{
				$css = explode(",", C_HOJA_CSS);
				foreach($css as $k => $v){
					$this->style(trim($v)); 
				}//next
			}
		}// end if
		
		if(defined('C_JAVASCRIPT')){
			if(is_array(C_JAVASCRIPT)){
				foreach(C_JAVASCRIPT as $js){
					if($js[1]){
						$this->js_post($js[0]);
					}else{
						$this->js($js[0]);
					}
				}
			}else{
				$js = explode(",", C_JAVASCRIPT);
				foreach($js as $k => $v){
					$this->js_post(trim($v));
				}//next
			}
		}// end if


		//$this->style($path."css/debug.css");
		/*
		$this->js($path."jscript/funciones.js");
		$this->js($path."jscript/cls_calendario.js");
		
		
		$this->js($path."jscript/ffunciones.js");
		$this->js($path."jscript/cls_capa.js");
		$this->js($path."jscript/cls_menu.js");
		

		$this->js_post($path."jscript/funciones_nuevas.js");
		$this->js_post($path."jscript/cls_validacion.js");
		$this->js_post($path."jscript/elementos.js");
		$this->js_post($path."jscript/multiple.js");
		$this->js_post($path."jscript/data_check.js");
		$this->js_post($path."jscript/formulario.js");
		$this->js_post($path."jscript/lista_set.js");
		$this->js_post($path."jscript/cesta2.js");
		$this->js_post($path."jscript/cls_grid.js");
		$this->js_post($path."jscript/multiple.js");
		//$this->js_post($path."jscript/cls_Flotador.js");
		//$this->js_post($path."jscript/cmb_txt.js");

		$this->js_post($path."jscript/layer.js");
		$this->js_post($path."jscript/cmb_text.js");


		$this->js_post($path."jscript/cls_combo_text.js");
		//$this->js_post($path."jscript/cls_ajax.js");
		$this->js_post($path."jscript/funciones_extras.js");
		
		*/
		

		
		
		//********************
		
		
		$est = new cfg_estructura($this->est,$this->usuario);
		$est->vses = &$this->vses;
		$est->vform = &$this->vform;
		$est->vexp = &$this->vexp;
		$est->deb = &$this->deb;

		$est->cambio_est = $this->cambio_est;
		$est->panel_default = $this->panel_default;
		$est->ini_est = $this->ini_est;
		$menu = $est->ejecutar();
		if($est->clase != ""){
			$this->clase = $est->clase;
		}// end if

		
		$this->js_post($est->script,C_MODO_AGREGAR);
		$this->vses["SS_TITULO"] = $est->titulo;
		//********************
		$this->extraer_paneles($est->diagrama);
		foreach($this->paneles as $panel => $v){
			if(isset($menu[$panel]) and $menu[$panel]){
				$est->diagrama = str_replace("~~$panel~~",$menu[$panel],$est->diagrama);
			}// end if
		}// next
		$est->elem = &$this->elem;

		$est->crear_elem();

		//********************
		foreach($this->paneles as $panel => $v){
		
			if(isset($est->elem[$panel]) and $est->elem[$panel]->panel){
			
			
				$obj = $est->elem[$panel];
				
				$obj->vses = &$this->vses;
				$obj->vform = &$this->vform;
				$obj->vexp = &$this->vexp;
				$obj->deb = &$this->deb;
			
				
				$obj->metodo = $this->metodo;
				$obj->con_interaccion = $this->con_interaccion;
				$obj->est = $this->est;			
				$obj->ins = $this->ins; 
				$obj->tgt = $this->tgt; 

				$mele_script = $this->elemento_script."['$panel']";
				$ele_script = $this->elemento_script."_".$panel;
				$obj->mele_script = $mele_script;
				$obj->ele_script = $ele_script;

				$obj->panel_default = $this->panel_default;
				$obj->clase = $this->clase;

				$obj->sw = $this->sw;
				$obj->sw2 = $this->sw;
				
				
				$span = new cls_element_html("span");
				$span->id = "sg_panel_".$panel;
				$span->inner_html = $obj->control();
				
				if($obj->hidden){
					$span->style = "display:none";
				}
				
				$est->diagrama = str_replace("~~$panel~~",$span->control(),$est->diagrama);
				$this->js_post($obj->script,C_MODO_AGREGAR);
				

				if($obj->titulo != "" and $panel == $this->panel_default){
					$this->title = $obj->titulo;
				}// end if
				
				$this->sub_formulario[$panel]=$obj->sub_formulario;
				
				
			}// end if
		}// next
		$sub_diagrama="";
		//$isf = 0;
		if(1==0 and is_array($this->sub_formulario) and 1==0)
		foreach($this->sub_formulario as $panel => $forms){
			if(is_array($forms))
			foreach($forms as $k => $v){
				//$isf++;
				$panel_x = $k;
				$obj = new cls_panel;
				$obj->metodo = $this->metodo;
			
				$obj->vses = &$this->vses;
				$obj->vform = &$this->vform;
				$obj->vexp = &$this->vexp;
				$obj->deb = &$this->deb;
			
				$obj->vexp["PANEL_PADRE"] = $panel;
			
				$obj->con_interaccion = false;
				$obj->est = $this->est;			
				$obj->ins = $this->ins; 
				$obj->tgt = $this->tgt; 

				$obj->actualizado = true;
				$obj->panel = $panel_x;
				$obj->estructura = $this->est;
				$obj->elemento = 1;
				$obj->nombre = $v;
				$obj->modo = 1;//$rs["modo"];
				$obj->registro = "";//$rs["registro"];
				$obj->pagina = 1;//$rs["pagina"];
				$obj->para_obj = "";//$rs["para_obj"];
				$obj->dinamico = true;
				$obj->parametros = "";//$rs["parametros"];

		
				$mele_script = $this->elemento_script."['$panel_x']";
				$ele_script = $this->elemento_script."_".$panel_x;
				$obj->mele_script = $mele_script;
				$obj->ele_script = $ele_script;
//		

				$obj->panel_default = $this->panel_default;
				
				
				$obj->sw = $this->sw;
				$obj->sw2 = $this->sw;
				$control_x = $obj->control();
					//style='position:absolute; visibility:hidden; left: 285px; top: 1098px; width: 399px;'
				
				$sub_diagrama .= "<div id='sp_$panel_x' class='barra_titulo' style='position:absolute;width:".(($obj->sf_width)?$obj->sf_width:C_SF_WIDTH)."; visibility:hidden;'><div style='height:24px;width:".(($obj->sf_width)?$obj->sf_width:C_SF_WIDTH).";background: url(imagenes/barra_titulo_negra.png);'><img onclick=\"cerrar_subform('sp_$panel_x')\" src='imagenes/salir_rojo.png' class='x_salir'></div>".$control_x."</div>";
				
				$script_x = "pnl_p[\"$panel_x\"]=\"$panel\";";
				$script_x .= "\npnl_h[\"$panel\"]=\"$panel_x\";";
				$this->js_post($obj->script.$script_x,C_MODO_AGREGAR);
				//hr($panel_x."...".$v,"purple");
			}// next
		}// next

		$est->diagrama .= $sub_diagrama;//str_replace("~~sub_panel~~",$sub_diagrama,$est->diagrama);
		
		
		$this->js_post("\nvar VG_PANEL_DEFAULT = $this->panel_default;",C_MODO_AGREGAR);
		
		$this->body = $est->diagrama;
	}// end fucntion
	//===========================================================
	function crear_panel($obj){
		echo "ERROR por aquui no debe pasar";
		$obj->vses = &$this->vses;
		$obj->vform = &$this->vform;
		$obj->vexp = &$this->vexp;
	
		$obj->con_interaccion = $this->con_interaccion;
		$obj->est = $this->est;			
		$obj->ins = $this->ins; 
		$obj->tgt = $this->tgt; 
	
		$panel = $obj->panel;
		
		$mele_script = $this->elemento_script."[$panel]";
		$ele_script = $this->elemento_script."_".$panel;
		$obj->mele_script = $mele_script;
		$obj->ele_script = $ele_script;

		$obj->panel_default = $this->panel_default;

	return;	
		$est->diagrama = str_replace("~~$panel~~",$obj->control(),$est->diagrama);
		$this->js_post($obj->script,C_MODO_AGREGAR);
		

		if($obj->titulo != "" and $panel == $this->panel_default){
			$this->title = $obj->titulo;
		}// end if
		
		$this->sub_formulario[$panel]=$obj->sub_formulario;
	
	
	}// end function	
	//===========================================================
	function control($tipo_x=C_MODO_DOC){
		
		//********************
		if($est = $this->eval_mod($this->modulo)){
			$this->est = $est;
			$this->ses["EST_DEF"] = $this->est;
			
		}// end if
		//********************
		if($this->aut){
			$this->secuencia($this->param);
		}else{
			$this->salir();
		}// end if	
		//********************
		if($this->est != "" and $this->est != $this->ses["EST"]){
			$this->autorizar($this->ses["USUARIO"], $this->ses["CLAVE"], $this->est);
		}// end if
		//********************
		$this->crear_diagrama();
		//********************
		$this->ses["ELEM"] = $this->elem;
		$this->ses["EST"] = $this->est;
		//********************
		
		if($this->vses["DEBUG"] == "1"){
			//$this->js(C_PATH."js/sgDebug.js");
			$this->deb->vses = &$this->vses;
			$this->deb->vform = &$this->vform;
			$this->deb->vexp = &$this->vexp;
			
			
			$this->body .= $this->deb->control();
		}// end if
		foreach($this->ses["CSS"] as $k => $v){
			$this->style($v);
		}// next
		foreach($this->ses["JS"] as $k => $v){
			$this->js($v, true);
		}// next
		
		if($this->vses["DEBUG"] == "1"){
			$this->js_post(C_PATH."js/sgDebug.js");
		}
		
		//$this->class="sg-mod-$this->modulo sg-est-$this->est";
		
		$html = cls_documento::control();
		
		if($this->vses["DEBUG"]=="1"){
			global $tiempo_inicio;
			$tiempo_final = microtime(true);
			$tiempo = $tiempo_final - $tiempo_inicio;
			$span = new cls_element_html("span");
			$span->id = "span_tiempo";
			$span->inner_html = "<a href='".$this->vses[SS_PATH_QUERY]."?cfg_ins_aux=".$this->vses[SS_INS]."' target='_blank'>Query</a>
			<br><span style='background:yellow;color:red'>Tiempo: $tiempo</span>";
			//$span->style =  "display:none";
			$span->inner_html .= "<br><br>Las Globales";	
			foreach($this->ses as $k => $v){
				
				//$span->inner_html .= "<br>$k = $v";
			}// next
			$span->inner_html .= "<br><img src='".$this->vses[SS_PATH]."imagenes/sigefor.png'/>";
			//$html .= persiana("Otros: ",$span->id,$span->control());
			
			$html .= $this->deb->setWindow("sg_debug_extra", $span->control());
		}// end if 
		return $html;
	}// end function
	
	public function getJson(){
		//$est->elem = &$est->elem;
		
		
		if($this->aut){
			$this->secuencia($this->param);
		}else{
			$this->salir();
		}// end if	
		
		$request = array();
		
		
		foreach($this->elem as $panel => $obj){
			if($obj->actualizado){
				
			
			
			
				//$obj = $est->elem[$panel];
				
				$obj->vses = &$this->vses;
				$obj->vform = &$this->vform;
				$obj->vexp = &$this->vexp;
				$obj->deb = &$this->deb;
			
				
				$obj->metodo = $this->metodo;
				$obj->con_interaccion = $this->con_interaccion;
				$obj->est = $this->est;			
				$obj->ins = $this->ins; 
				$obj->tgt = $this->tgt; 

				$mele_script = $this->elemento_script."['$panel']";
				$ele_script = $this->elemento_script."_".$panel;
				$obj->mele_script = $mele_script;
				$obj->ele_script = $ele_script;

				$obj->panel_default = $this->panel_default;
				$obj->clase = $this->clase;

				$obj->sw = $this->sw;
				$obj->sw2 = $this->sw;
				
				//$est->diagrama = str_replace("~~$panel~~",$obj->control(),$est->diagrama);
				//$this->js_post($obj->script,C_MODO_AGREGAR);
				

				if($obj->titulo != "" and $panel == $this->panel_default){
					$this->title = $obj->titulo;
				}// end if
				
				
				$aux = $obj->control();
				
				$request[$panel] = new stdClass;
				$request[$panel]->targetId = "sg_panel_$panel";
				$request[$panel]->html = $aux;
				$request[$panel]->script = $obj->script;
				$request[$panel]->typeAppend = 1;
				$request[$panel]->hidden = $obj->hidden;
				$request[$panel]->titulo = $obj->titulo;
				
				
				
				
				//print_r($this->deb->getObj());exit;
			}
			
		}// next
		
		
		
		if($this->vses["DEBUG"] == "1"){
			
			if(!isset($this->param["ondebug"]) or $this->param["ondebug"] !== false or $this->param["ondebug"] !== "0"){
				$request[-1] = new stdClass;
				$request[-1]->debug = $this->deb->getObj();
			}
			
			
			
		}
		
		
		/*
		$f = new stdClass;
		
		$f->targetId = "sg_panel_4";
		$f->html = "<b class='z'>Tres</b>  Chao";
		$f->script = "";
		$f->css = ".z{font-weight:bold;color:orange;}";
		$f->typeAppend = 0;
		$f->style = new stdClass;
		//$f->propertys->innerHTML = "QUE";
		//$f->propertys->placeholder = "....SELECCIONE";
		$f->style->border = "4px solid pink";
		
		*/
		$this->ses["ELEM"] = $this->elem;
		return json_encode($request); 
	}
	
	public function render(){
		
		
		if(isset($this->vform["cfg_async_aux"]) and $this->vform["cfg_async_aux"]){
			$this->async = true;
			//echo "hola";exit;
		}else{
			//echo "bye";exit;
		}
		if(isset($this->vform["cfg_panel_aux"])){
			$this->panel = $this->vform["cfg_panel_aux"];
		}
		
		$this->deb = new cls_debug();
		$this->eval_metodo();
		$this->crear_var_session();
		//********************
		if($this->ses["USUARIO"] != ""){
			$this->usuario = $this->ses["USUARIO"];
			$this->clave = $this->ses["CLAVE"];
			$this->mod = $this->ses["MOD"];
			$this->est = $this->ses["EST"];
			$this->elem = $this->ses["ELEM"];
			$this->aut = $this->ses["AUT"];
			
			$this->last_panel = $this->ses["last_panel"];
			
			$this->usuario_act = $this->usuario;
			$this->clave_act = $this->clave;
			$this->est_act = $this->est;
			if($this->async){
				foreach($this->elem as $panel => $obj){
					if($panel == 4 or $panel == $this->panel){
						$obj->actualizado = true;
					}else{
						$obj->actualizado = false;
					}
				}
			}
			
		}// end if
		//********************
		if(!isset($this->ses[C_SW])){
			$this->ses[C_SW] = false;
		}
		
		if(isset($this->vform["cfg_sw_aux"]) and $this->vform["cfg_sw_aux"] != $this->ses[C_SW]){
			$this->ses[C_SW] = $this->vform["cfg_sw_aux"];
			$this->act = true;
		}else{
			$this->act = false;
		}// end if
		$this->sw = (isset($this->vform["cfg_sw_aux"]))?$this->vform["cfg_sw_aux"]:false;
		//********************
		$this->extraer_param();
		
		$this->ses["last_panel"] = $this->panel;
		
		if($this->async){
			
			echo $this->getJson();
			
		}else{
			header('Content-Type: text/html; charset='.SS_CHARSET);
			echo $this->control();
		}
		
		
	}
}// end if

// SIGEFOR ===================================================



//********************



$s = new cls_sigefor();

$s->css_files = $cssFiles;
$s->js_files = $jsFiles;


$s->render();
//********************
/*
if($s->vses["DEBUG"]=="1"){
	$tiempo_final = microtime(true);
	$tiempo = $tiempo_final - $tiempo_inicio;
	$span = new cls_element_html("span");
	$span->id = "span_tiempo";
	$span->inner_html = "<hr><a href='".$s->vses[SS_PATH_QUERY]."?cfg_ins_aux=".$s->vses[SS_INS]."' target='_blank'>Query</a>
	<br><span style='background:yellow;color:red'>Tiempo: $tiempo</span>";
	$span->style =  "display:none";
	$span->inner_html .= "<br><br>Las Globales";	
	foreach($s->ses as $k => $v){
		$span->inner_html .= "<br>$k = $v";
	}// next
	$span->inner_html .= "<br><img src='".$s->vses[SS_PATH]."imagenes/sigefor.png'/>";
	echo persiana("Otros: ",$span->id,$span->control());
}// end if 
*/
// SIGEFOR ===================================================
?>