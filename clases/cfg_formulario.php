<?php
/*****************************************************************
creado: 25/04/2007
modificado: 05/10/2007
por: Yanny Nuñez
*****************************************************************/
define("C_FORMULARIO_NORMAL","0");
define("C_FORMULARIO_PATRON","1");
define("C_FORMULARIO_DISENO","2");
define("C_FORMULARIO_ARCHIVO","3");
define("C_FORMULARIO_DINAMICO","4");


require_once("cfg_campo.php");
//require_once("cls_mysql.php");
//===========================================================
class cfg_formulario{
	var $formulario = "";
	//===========================================================
	var $forma = "";
	var $titulo = "";	
	var $clase = "";
	var $tipo = C_FORMULARIO_NORMAL;
	var	$plantilla = "";
	var $query = "";
	var $grupos = "";							
	var $divisiones = "";							
	var $variables = "";							
	var	$parametros = "";
	var $expresiones = "";
	var $estilo = "";							
	var	$propiedades = "";
	var $eventos = "";	
	
	var $con_formulario	= false;
	//===========================================================
	var	$navegador_req = C_NAV_STD_PETICION;
	var	$navegador_ins = C_NAV_STD_INSERT;
	var	$navegador_upd = C_NAV_STD_UPDATE;
	var	$navegador_con = C_NAV_STD_CONSULTA;
	var $consulta = "";
	var $diagrama = "";
	//===========================================================
	var $cfg_formularios = C_CFG_FORMULARIOS;
	var $cfg_formas = C_CFG_FORMAS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	//===========================================================
	var $configurado = false;
	var $nro_pag = 0;
	var $con_ind = "si";
	var $indicador = " *";
	var $de_sesion = false;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	
	var $mele_script = ""; 
	var $ele_script = ""; 
	var $error_restriccion;
	var $error_duplicado;			
	var $error_general;

	public $conexion = false;
	
	public $sin_comillas = false;
	public $con_comillas = true;
	
	public $modo = false;
	public $con_valores = false;
	public $valores = false;
	public $registro = false;
	public $pkey = false;
	
	public $panel = false;
	public $tablas_aux = false;
	
	public $modo_auto = false;
	public $msg_ok = false;
	public $mostrar_error = false;
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	//===========================================================
	function ejecutar($formulario_x = ""){
		if ($formulario_x != ""){
			$this->formulario = $formulario_x;
		}// end if
		
		
		$this->forma = $this->formulario;
		$cn = &$this->conexion;
		$this->vexp["MODO_FORMULARIO"] = $this->modo;
		//===========================================================
		$cn->query = "	SELECT 
						a.forma, a.titulo, a.clase, a.tipo, a.plantilla,
						a.query, a.grupos, a.divisiones, a.clave, a.variables,
						a.parametros, a.expresiones,
						a.estilo, a.propiedades, a.eventos,
						
						b.formulario, b.forma as forma_b, b.titulo as titulo_b, b.clase as clase_b,
						b.navegador_req, b.navegador_ins, b.navegador_upd, b.navegador_con,
						b.consulta, b.variables as variables_b,
						b.parametros as parametros_b, b.expresiones as expresiones_b, 
						b.estilo as estilo_b, b.propiedades as propiedades_b

						FROM cfg_formas as a
						LEFT JOIN cfg_formularios as b ON a.forma = b.forma AND (b.formulario = '$this->formulario')
						WHERE b.formulario = '$this->formulario' OR a.forma = '$this->formulario'";
		$cn->ejecutar();
		if($rs = $cn->consultar()){
			$this->vpara = &$rs;
			$this->configurado = true;
			$this->forma = &$rs["forma"];
			$this->titulo = &$rs["titulo"];
			if($rs["clase"] != ""){
				$this->clase = &$rs["clase"];
			}// end if
			if ($rs["tipo"]){
				$this->tipo = &$rs["tipo"];
			}//end if
			$this->plantilla = &$rs["plantilla"];
			$this->query = &$rs["query"];
			$this->grupos = &$rs["grupos"];
			$this->divisiones = &$rs["divisiones"];
			$this->clave = &$rs["clave"];
			$this->variables = &$rs["variables"];
			$this->parametros = &$rs["parametros"];
			$this->expresiones = &$rs["expresiones"];
			$this->estilo = &$rs["estilo"];
			$this->propiedades = &$rs["propiedades"];
			$this->eventos = &$rs["eventos"];
			if($rs["forma_b"]){
				if ($rs["titulo_b"] != ""){
					$this->titulo = $rs["titulo_b"];
				}//end if
				if ($rs["clase_b"] != ""){
					$this->clase = $rs["clase_b"];
				}//end if
				$this->navegador_req = &$rs["navegador_req"];
				$this->navegador_ins = &$rs["navegador_ins"];
				$this->navegador_upd = &$rs["navegador_upd"];
				$this->navegador_con = &$rs["navegador_con"];
				$this->consulta = &$rs["consulta"];
				if ($rs["estilo_b"] != ""){
					$this->estilo = $rs["estilo_b"];
				}//end if
				$this->parametros = ajustar_sep($this->parametros).$rs["parametros_b"];
				
				$this->expresiones = ajustar_sep($this->expresiones).$rs["expresiones_b"];
				if ($rs["variables_b"] != ""){
					$this->variables = $rs["variables_b"];
				}//end if
				$this->estilo = ajustar_sep($this->estilo).$rs["estilo_b"];
				$this->propiedades = ajustar_sep($this->propiedades).$rs["propiedades_b"];
				$this->con_formulario = true;
			}// end if
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			$this->expresiones = $this->evaluar_todo($this->expresiones);			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = array_merge($this->vexp, $prop);
			}// end if
			//===========================================================
			if($this->sin_comillas == "si"){
				$this->con_comillas = false;
			}// end if
			//===========================================================
			$this->query = $this->evaluar_todo($this->query, $this->con_comillas);
			if ($this->query == "" or $this->query == null){
				$this->query = "SELECT * FROM $this->forma ";
			}else if($this->query != "" and !preg_match("|[ ]+|", trim($this->query))){
				$this->query = "SELECT * FROM $this->query ";
			}// end if	
			$this->grupos =  $this->evaluar_todo($this->grupos);
			$this->divisiones =  $this->evaluar_todo($this->divisiones);
			$this->estilo =  $this->evaluar_todo($this->estilo);
			$this->propiedades =  $this->evaluar_todo($this->propiedades);
			$this->eventos =  $this->evaluar_todo($this->eventos);
			//===========================================================
			if($this->tipo == C_FORMULARIO_PATRON or $this->tipo == C_FORMULARIO_DISENO){
				$this->diagrama =  $this->evaluar_var($this->leer_diagrama($this->plantilla));
			}// end if
			//===========================================================
			if($this->clase!=""){
				if($this->clase_formulario == ""){
					$this->clase_formulario = $this->clase."_formulario";
				}// end if
				if($this->clase_caption == ""){
					$this->clase_caption = $this->clase."_for_caption";
				}// end if
				if($this->clase_titulo == ""){
					$this->clase_titulo = $this->clase."_for_titulo";
				}// end if
				if($this->clase_etiqueta == ""){
					$this->clase_etiqueta = $this->clase."_for_etiqueta";
				}// end if
				if($this->clase_control == ""){
					$this->clase_control = $this->clase."_for_control";
				}// end if
				if($this->clase_grupo == ""){
					$this->clase_grupo = $this->clase."_for_grupo";
				}// end if
				if($this->clase_indicador == ""){
					$this->clase_indicador = $this->clase."_for_indicador";
				}// end if
			}// end if
			//===========================================================
			$this->eval_campos();
			return true;
		}// end if
		$this->query = "SELECT * FROM $this->formulario ";
		$this->eval_campos();
		return false;
	}// end function
	//===========================================================
	function eval_campos(){
		$config = new cfg_campo;

		$config->de_sesion = $this->de_sesion;
		$config->con_valores = $this->con_valores;
		$config->valores = $this->valores;
		
		$config->panel = $this->panel;
		$config->vses = &$this->vses;
		$config->vform = &$this->vform;
		$config->vexp = &$this->vexp;
		
		$config->deb = &$this->deb;
		$config->debug = &$this->debug;
		$config->clase = $this->clase;
		$config->registro = $this->registro;
		$config->modo = &$this->modo;
		if($this->pkey!=""){
			$config->pkey = $this->pkey;
		}// end if
		
		$config->ejecutar($this->formulario, $this->query);
		$this->nro_campos = $config->nro_campos;
		
		$this->cfg = &$config;
		return true;		
	}// end function
	//===========================================================
	function leer_diagrama($plantilla = ""){
		if($plantilla == ""){
			return "";
		}// end if
		$cn = &$this->conexion;
		$cn->query = "SELECT diagrama FROM $this->cfg_plantillas WHERE plantilla = '$plantilla'";
		if($rs = $cn->consultar($cn->ejecutar())){
			return  $rs["diagrama"];
		}// end if
		return false;
	}// end function
	//===========================================================
	function eval_q_para($query_x=""){
		if($query_x == ""){
			return array();
		}// end if
		$cn = &$this->conexion;
		$cn->query = $query_x;
		return $cn->ejecutar();
	}// end function
	
	//===========================================================
	function evaluar_error($meta_error){
		switch($meta_error){
		case C_MERROR_RESTRICCION:
			return $this->error_restriccion;
			break;
		case C_MERROR_DUPLICADO:
			return $this->error_duplicado;			
			break;
		default:
			return $this->error_general;
			break;
		}// end switch
	}//	 end if
	
	
	
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
}// end class
?>