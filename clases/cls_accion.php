<?php
/*****************************************************************
creado: 26/03/2007
modificado: 04/07/2007
por: Yanny Nuñez
*****************************************************************/
//===========================================================






class cfg_acciones{
	var $objeto="";
	var $parametro="";
	var $modo="";
	var $pagina="";
	var $registro="";
	var $target="";
	var $estructura="";
	var $actualizar="";
	var $accion="";
	var $comando = "";
	var $destino = array("","formulario","consulta","busqueda","reporte","articulo","pagina","enlace","marco","sub_menu","menu","vista","forma","email","catalogo");
	function control($rs){
		$param = "";
		if ($rs["destino"]==C_ELEM_NINGUNO){
			$this->objeto = $rs["destino"];			
			$param .= ";";
		}// end if
		if ($rs["destino"]>=1 and $rs["destino"]<=8 or ($rs["destino"]>=11 and $rs["destino"]<=14)){
			$this->objeto = $rs["destino"];			
			$param .= "objeto:".$this->destino[$rs["destino"]].";";
		}// end if
		if ($rs["parametro"]!="" and $rs["parametro"]!= null){
			$this->parametro = $rs["parametro"];			
			$param .= "parametro:".$rs["parametro"].";";
		}// end if
		if ($rs["modo"]!="" and $rs["modo"]!= null){
			$this->modo = $rs["modo"];			
			$param .= "modo:".$rs["modo"].";";
		}// end if
		if ($rs["accion"]!="" and $rs["accion"]!= null){
			$this->accion = $rs["accion"];			
			$param .= "accion:".$rs["accion"].";";
		}// end if
		if ($rs["cmd_ini"]!="" and $rs["cmd_ini"]!= null){
			$this->cmd_ini = $rs["cmd_ini"];			
			$param .= "cmd_ini:".$rs["cmd_ini"].";";
		}// end if
		if ($rs["cmd_fin"]!="" and $rs["cmd_fin"]!= null){
			$this->cmd_fin = $rs["cmd_fin"];			
			$param .= "cmd_fin:".$rs["cmd_fin"].";";
		}// end if

		if ($rs["proc_ini"]!="" and $rs["proc_ini"]!= null){
			$param .= "proc_ini:".$rs["proc_ini"].";";
		}// end if
		if ($rs["proc_fin"]!="" and $rs["proc_fin"]!= null){
			$param .= "proc_fin:".$rs["proc_fin"].";";
		}// end if
		if ($rs["registro"]!="" and $rs["registro"]!= null){
			$this->registro = $rs["registro"];			
			$param .= "reg:".$rs["registro"].";";
		}// end if
		if ($rs["estructura"]!="" and $rs["estructura"]!= null){
			$this->estructura = $rs["estructura"];			
			$param .= "est:".$rs["estructura"].";";
		}// end if
		if ($rs["pagina"]!="" and $rs["pagina"]!= null){
			$this->pagina = $rs["pagina"];			
			$param .= "pagina:".$rs["pagina"].";";
		}// end if
		if ($rs["pag_form"]!="" and $rs["pag_form"]!= null){
			$this->pag_form = $rs["pag_form"];			
			$param .= "pag_form:".$rs["pag_form"].";";
		}// end if
		if ($rs["panel_destino"]!="" and $rs["panel_destino"]!= null){
			$this->panel = $rs["panel_destino"];			
			$param .= "panel:".$rs["panel_destino"].";";
		}// end if
		if ($rs["referencia"]!="" and $rs["referencia"]!= null){
			$this->panel = $rs["referencia"];			
			$param .= "ref:".$rs["referencia"].";";
		}// end if
		return $param;		
	}// end function
}// end class
?>