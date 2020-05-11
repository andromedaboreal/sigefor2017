<?php
/*****************************************************************
creado: 20/06/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/

class cfg_comando{
         
	var $comando="";
	var $titulo="";
	var $acciones="";
	var $procedimiento="";
	var $expresiones="";
	var $variables="";
	var $parametros="";
	var $mensaje="";
	var $status="";

	var $abortar = false;
	var $cfg_comandos = C_CFG_COMANDOS;
	
	var $vses = array();
	var $vform = array();
	
	var $vreg = array();
	var $vpara = array();
	var $vexp = array();
	
	public $ondebug = true;
	public $deb = false;
	
	function __construct(){
		$this->conexion = sgConnection();
		
		if(isset($this->vses["DEBUG"]) and $this->vses["DEBUG"] == "1"){
			$this->ondebug = true;
		}// end if
		
	}
	
	//===========================================================
	function ejecutar($cmd_x="",$abortar_x=false){
		if($abortar_x){
			$this->abortar = $abortar_x;
			return false;
		}// end if
		if($cmd_x!=""){
			$this->comando = $cmd_x;
		}// end if
		
		if($this->ondebug){
			$this->_db = $this->deb->setObj(array(
				"panel" => false,
				"tipo" => "comando",
				"nombre" => $this->comando,
				"t&iacute;tulo" => &$this->titulo
			
			));
		}
		
		//===========================================================
		
		$cn = &$this->conexion;
		$cn->query = "	SELECT 
						comando, titulo, acciones, procedimiento, expresiones, variables, parametros, mensaje, status 

						FROM $this->cfg_comandos
						WHERE comando = '$this->comando' and status = '1'";
						
		$result = $cn->ejecutar();
		
		if($rs=$cn->consultar($result)){
			//===========================================================
			
			
			$this->comando = &$rs["comando"];
			$this->titulo = &$rs["titulo"];
			$this->acciones = &$rs["acciones"];
			$this->procedimiento = &$rs["procedimiento"];
			$this->expresiones = &$rs["expresiones"];
			$this->variables = &$rs["variables"];
			$this->parametros = &$rs["parametros"];
			$this->mensaje = &$rs["mensaje"];
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->abortar){
				return false;
			}// end if
			//===========================================================
			$this->expresiones = $this->evaluar_todo($this->expresiones);			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = array_merge($this->vexp,$prop);
			}// end if
			//===========================================================
			$this->variables = $this->evaluar_todo($this->variables);
			if($prop = extraer_para($this->variables)){
				$this->vses = array_merge($this->vses,$prop);
			}// end if
			
			//$this->acciones = $this->evaluar($this->evaluar_var($this->acciones));
			$acciones = $jj = $this->acciones;	
			$this->acciones = reparar_sep($this->evaluar_todo($this->acciones));
			$this->mensaje = $this->evaluar_todo($this->mensaje);			
			//===========================================================

			if($this->vses["DEBUG"] == "1"){
			
				$this->deb->dbg("-",$this->comando,$this->titulo,"comando=$this->comando","cm","<br><b>Acciones:</b> ".$jj,"<br><b>Acciones:</b> ".$this->acciones);
			}// end if				

			if($this->ondebug){
				$this->_db->set(array(
					"acciones" => $acciones,
					"acciones_r" => $this->acciones,

				));
			}
			
			$this->eval_proc();
			//===========================================================
		}// end if
	}// end function
	//===========================================================
	function eval_proc($proc_x=""){
		require_once("cfg_procedimiento.php");
		if($proc_x!=""){
			$this->procedimiento = $proc_x;
		}// end if
		$proc = new cfg_procedimiento();
		$proc->vses = &$this->vses;
		$proc->vform = &$this->vform;
		$proc->vexp = &$this->vexp;
		$proc->deb = &$this->deb;
		$proc->ejecutar($this->procedimiento);
	}// end function
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		try{
			$q = eval_prop($q);
		}catch (Exception $e) {
			hr("Error en $q","aqua","black");
			return "";
		}
		return $q.C_SEP_Q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas);		
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);		
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q.C_SEP_Q;
	}// end function
	//===========================================================
	function evaluar_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas,false);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
}// end class
?>