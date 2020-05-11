<?php
/*****************************************************************
creado: 25/04/2007
modificado: 15/07/2007
por: Yanny Nuñez
*****************************************************************/
//require_once("cls_mysql.php");
require_once("cfg_formulario.php");
require_once("cfg_campo.php");
require_once("funciones.php");

class cls_sub_formulario extends cfg_formulario{
	var $formulario = "";
	//===========================================================
	function control($formulario_x = ""){
		if ($formulario_x!=""){
			$this->formulario = $formulario_x;
		}// end if
		$this->ejecutar();	
		
		$this->cfg = new cfg_campo;
		$this->cfg->vses = &$this->vses; 
		$this->cfg->vform = &$this->vform; 
		$this->cfg->vexp = &$this->vexp; 
		$this->cfg->sub_form = true;
		$this->cfg->form_script = $this->form_script;
		$cfg = &$this->cfg;
		
		if($this->clave!=""){
			$this->registro = $this->clave;
		}// end if	

		if($this->registro!=""){
			$cfg->registro = $this->registro;
		}// end if
		$cfg->ejecutar($this->formulario,$this->query);
		//hr( $cfg->campo[0]->tabla);
		
		
		$cfg->crear_data();
	}// end function
}// end class

?>