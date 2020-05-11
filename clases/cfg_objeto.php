<?php
/*****************************************************************
creado: 30/09/2008
modificado: 20/11/2008
por: Yanny Nuñez
*****************************************************************/
class cfg_objeto{
	var $abortar = false;
	//===========================================================
	var $objeto = "";
	var $funcion = "";
	//===========================================================
	var $conexion = false;
	var $acciones = array();
	//===========================================================
	var $cfg_objetos = C_CFG_OBJETOS;
	var $cfg_obj_acc = C_CFG_OBJ_ACC;
	//===========================================================
	var $vses = array();
	var $vfor = array();
	var $vreg = array();
	var $vpara = array();
	var $vexp = array();
	//===========================================================
	function ejecutar($obj_x="", $func_x=""){
		if($this->vexp["OBJ_ABORTAR"]){
			$this->abortar = true;
			return false;
		}// end if
		if($obj_x != ""){
			$this->objeto = $obj_x;
		}// end if
		if($func_x != ""){
			$this->funcion = $func_x;
		}// end if
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;
		$cn->query = "	SELECT 	a.objeto, funcion, accion
						FROM $this->cfg_obj_acc AS a
						INNER JOIN $this->cfg_objetos AS b ON b.objeto = a.objeto
						WHERE a.objeto = '$this->objeto' AND funcion = '$this->funcion'
						AND b.status=1";
		$result = $cn->ejecutar();
		$this->acciones = array();
		while($rs = $cn->consultar($result)){
			$this->acciones["accion"] = $rs["accion"];
		}// end if
		return $this->acciones;
	}// end function
	//===========================================================
}// end class
?>