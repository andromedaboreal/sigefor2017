<?php 
/*****************************************************************
creado: 23/04/2007
modificado: 04/07/2007
por: Yanny Nuñez
*****************************************************************/
//===========================================================
class cfg_modulo{
	var $modulo = "";
	var $titulo = "";
	var $estructura = "";
	var $login = "";
	var $password = "";
	var $configuracion = "";
	
	var $cfg_modulos = C_CFG_MODULOS;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	var $vst = array();
	public $cfg = false;
	//===========================================================
	
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	
	function ejecutar($modulo_x){
		
		$this->cfg = new stdClass;
		
		if($modulo_x!=""){
			$this->modulo = $modulo_x;
		}// end if
		$cn = $this->conexion;
		$cn->query = "SELECT * FROM $this->cfg_modulos WHERE modulo = '$this->modulo'";
		$result = $cn->ejecutar();
		
		if($rs=$cn->consultar($result)){       

			$this->modulo = $rs["modulo"];
			$this->titulo = $rs["titulo"];
			$this->estructura = $rs["estructura"];
			$this->login = $rs["login"];
			$this->password = $rs["password"];
			$this->configuracion = $rs["configuracion"];


			if($prop = extraer_para($this->configuracion)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					
					$this->cfg->$para = $valor;
					
					//eval("\$this->cfg->$para=\"$valor\";");
				}// next
			}// end if


			$this->archivos = $rs["archivos"];

			$this->tema = $rs["tema"];
			$this->debug = $rs["debug"];
			
			$this->vses["SS_MODULO_PRINCIPAL"] = $this->modulo;
			$this->vses["SS_ESTRUCTURA_PRINCIPAL"] = $this->modulo;
			return true;
		}else{
			hr($cn->query,"red");
		}// end if
		return false;		
	}// end fucntion
	//===========================================================
}// end class
?>