<?php 
/*****************************************************************
creado: 23/04/2007
modificado: 04/07/2007
por: Yanny Nuñez
*****************************************************************/
//===========================================================
class cfg_secuencia{

	var $secuencia = "";
	var $titulo = "";
	var $parametros = "";
	var $status = "";

	var $modulo = "";
	
	var $estructura = "";
	var $login = "";
	var $password = "";
	var $config_ini = "";
	var $cfg_secuencias = C_CFG_SECUENCIAS;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	var $vst = array();
	
	//===========================================================
	function ejecutar($secuencia_x){
		if($secuencia_x!=""){
			$this->secuencia = $secuencia_x;
		}// end if
		$cn = new cls_conexion;
		$cn->query = "SELECT * FROM $this->cfg_secuencias WHERE secuencia = '$this->secuencia'";
		$result = $cn->ejecutar();
		if($rs=$cn->consultar($result)){       
			$this->secuencia = $rs["secuencia"];
			$this->titulo = $rs["titulo"];
			$this->parametros = $rs["parametros"];
			$this->status = $rs["status"];
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			return true;
		}// end if
		return false;		
	}// end fucntion
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
		$q = eval_prop($q);
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
	//===========================================================
	
}// end class
?>