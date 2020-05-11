<?php
/*****************************************************************
creado: 25/04/2007
modificado: 05/10/2007
por: Yanny Nuñez
*****************************************************************/

//===========================================================
class cfg_grafico{
	var $grafico = "";
	//===========================================================
	var $titulo = "";	
	var $tipo = 2;
	var $query = "";
	var	$parametros = "";
	var $expresiones = "";
	var $ancho = "500";
	var $alto = "400";
	//===========================================================
	//var $cfg_graficos = C_CFG_GRAFICOS;
	var $cfg_formas = C_CFG_FORMAS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	//===========================================================
	var $configurado = false;
	var $con_comillas = true;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	//===========================================================
	function ejecutar($grafico_x = ""){
		if ($grafico_x != ""){
			$this->grafico = $grafico_x;
		}// end if
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;
		//===========================================================
		$cn->query = "	SELECT * 
						FROM cfg_graficos as a
						WHERE a.grafico = '$this->grafico'";
		$cn->ejecutar();
		
		if($rs = $cn->consultar()){
			$this->vpara = &$rs;
			$this->configurado = true;
			
			
			$this->grafico = &$rs["grafico"];
			$this->titulo = &$rs["titulo"];
			$this->query = &$rs["query"];
			$this->navegador = &$rs["navegador"];
			$this->tipo = &$rs["tipo"];
			$this->parametros = &$rs["parametros"];
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
				$this->query = "SELECT * FROM $this->grafico ";
			}else if($this->query != "" && !preg_match("|[ ]+|", trim($this->query))){
				$this->query = "SELECT * FROM $this->query ";
			}// end if	
			//$this->grupos =  $this->evaluar_todo($this->grupos);
			//===========================================================
			return true;
		}// end if
		$this->query = "SELECT * FROM $this->grafico ";
		return false;
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
