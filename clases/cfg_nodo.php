<?php
/*****************************************************************
creado: 30/09/2008
modificado: 20/11/2008
por: Yanny Nuñez
*****************************************************************/
class cfg_nodo{
	var $abortar = false;
	//===========================================================
	var $flujo = "";
	var $nodo = "";
	var $orden = "";
	var $objeto = "";
	var $funcion = "";
	var $parametros = "";	
	//===========================================================
	var $nodo_i = "";
	var $conexion = false;
	//===========================================================
	var $cfg_flujos = C_CFG_FLUJOS;
	var $cfg_nodos = C_CFG_NODOS;
	//===========================================================
	var $vses = array();
	var $vfor = array();
	var $vreg = array();
	var $vpara = array();
	var $vexp = array();
	//===========================================================
	function ejecutar($nodo_x=""){
		if($this->vexp["NDO_ABORTAR"]){
			$this->abortar = true;
			return false;
		}// end if
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;
		$cn->query = "	SELECT 	a.flujo, nodo, a.titulo,  
								orden, objeto, funcion, 
								parametros, a.status 
						FROM $this->cfg_nodos as a
						INNER JOIN $this->cfg_flujos as b ON b.flujo = a.flujo
						WHERE a.status = 1 AND b.status = 1
						";
		if($nodo_x == "-1"){
			$cn->query .= " AND b.flujo = '$this->flujo' AND a.orden < $this->orden ORDER BY a.orden DESC";
		}else if($nodo_x == "-2"){
			$cn->query .= " AND b.flujo = '$this->flujo' AND a.orden > $this->orden ORDER BY a.orden ";
		}else{
			$cn->query .= " AND a.nodo = '$nodo_x'";
		}// end if
		$cn->query .= " LIMIT 1";
		$this->nodo_i = "";
		$result = $cn->ejecutar();
		if($rs = $cn->consultar($result)){
			$this->flujo = $rs["flujo"];
			$this->nodo = $rs["nodo"];
			$this->orden = $rs["orden"];
			$this->objeto = $rs["objeto"];
			$this->funcion = $rs["funcion"];
			$this->parametros = $rs["parametros"];
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
			if($this->nodo_i!=""){
				return $this->ejecutar($this->nodo_i);
			}// end if
			return true;
		}// end if
		return false;
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