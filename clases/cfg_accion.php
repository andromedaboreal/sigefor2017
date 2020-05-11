<?php
/*****************************************************************
creado: 01/10/2008
modificado: 20/11/2008
por: Yanny Nuñez
*****************************************************************/
class cfg_accion{
	var $proc_ini;
	var $pro_fin;
	var $cmd_ini;
	var $cmd_fin;
	var $secuencia;
	var $estructura;
	var $interaccion;
	var $accion;
	var $rs;
	
	var $panel_actual;
	var $panel_default;
	var $conexion;
	
	
	//===========================================================
	var $cfg_acciones = C_CFG_ACCIONES;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	
	public $valid = true; 
	
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	//===========================================================
	function ejecutar($accion_x){
		if($accion_x!=""){
			$this->accion = $accion_x;
		}// end if
		
		//===========================================================
		$cn = &$this->conexion;
		$cn->query = "	SELECT *
						FROM $this->cfg_acciones
						WHERE accion = '$this->accion' AND status='1'
						";
		$result = $cn->ejecutar();
		//===========================================================
		if($rs = $cn->consultar_asoc($result)){
			$this->vpara = $rs;
			
			foreach($rs as $k => $v){
				$this->param[$k] = $v;
			}
			/*
			$this->param["accion"] = $rs["accion"];
			$this->param["titulo"] = $rs["titulo"];
			$this->param["panel"] = $rs["panel"];
			$this->param["elemento"] = $rs["elemento"];
			$this->param["nombre"] = $rs["nombre"];
			$this->param["modo"] = $rs["modo"];
			//$this->param["registro"] = $rs["registro"];
			$this->param["pagina"] = $rs["pagina"];
			$this->param["referencia"] = $rs["referencia"];
			$this->param["obj"] = $rs["obj"];
			$this->param["obj_funcion"] = $rs["obj_funcion"];
			$this->param["interaccion"] = $rs["interaccion"];
			$this->param["secuencia"] = $rs["secuencia"];
			$this->param["nodo"] = $rs["nodo"];
			$this->param["proc_ini"] = $rs["proc_ini"];
			$this->param["proc_fin"] = $rs["proc_fin"];
			$this->param["cmd_ini"] = $rs["cmd_ini"];
			$this->param["cmd_fin"] = $rs["cmd_fin"];
			$this->param["estructura"] = $rs["estructura"];			
			$this->param["parametros"] = $rs["parametros"];
			$this->param["funcion"] = $rs["funcion"];
			$this->param["sesion"] = $rs["sesion"];
			
			
			$this->param["registro"] = $this->evaluar_todo($rs["registro"]);
			$this->param["parametros"] = $this->evaluar_todo($this->param["parametros"]);
			$this->param["mensaje"] = $this->evaluar_todo($rs["mensaje"]);
			*/
			$this->param["registro"] = $this->evaluar_todo($this->param["registro"]);
			$this->param["parametros"] = $this->evaluar_todo($this->param["parametros"]);
			$this->param["mensaje"] = $this->evaluar_todo($this->param["mensaje"]);
			
			if(!$this->valid){
				$this->param["validar"] = 0;
			}
			
			//===========================================================
			if($prop = extraer_para($this->param["parametros"])){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->param[\"$para\"]=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->param["elemento"] == C_ELEM_ACCION){
				$this->ejecutar($this->param["nombre"]);
			}// end if
		}// end if
		
	}// end fucntion
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
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
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		return $q;
	}// end function
	//===========================================================
	function evaluar_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
}// end class
?>