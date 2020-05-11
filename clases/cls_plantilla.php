<?php
define ("C_OBJETO_VAR","1");
define ("C_OBJETO_PRI","2");
define ("C_OBJETO_NO_VAR","0");
class cls_plantillaXXX{
	var $plantilla = "";
	var $titulo = "";
	var $diagrama = "";
	var $situacion = "";
	var $paneles;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	var $cfg_pla_pan = C_CFG_PLA_PAN;
	var $vform;
	var $vses;	
	//===========================================================
	function extraer_paneles($plan_x){
		$exp = "|--([0-9]+)--|";
		if(preg_match_all($exp, $plan_x, $c)){
		foreach($c[1] as $a => $b)
			$this->paneles[$b]="1";
		}// next
	}// end function
	//===========================================================
	function ejecutar($nombre_x=""){
		if($nombre_x!=""){
			$this->nombre = $nombre_x;
		}// end if
		//======================================
		$cn = new cls_conexion;
		$cn->query = "
			SELECT $this->cfg_plantillas.plantilla,  titulo, diagrama, status,  
				panel, objeto, parametro, modo, registro, pagina, tipo_panel 
			FROM $this->cfg_plantillas
			LEFT JOIN $this->cfg_pla_pan ON $this->cfg_pla_pan.plantilla = $this->cfg_plantillas.plantilla 
			WHERE $this->cfg_plantillas.plantilla = '$this->nombre'";
		$result = $cn->ejecutar();
		//======================================
		$this->nro_obj=0;
		$this->pan_var = "";
		$this->nro_obj = $cn->nro_filas;
		$i=0;
		//======================================
		while ($rs = $cn->consultar($result)){
			$i++;
			$panel = $rs["panel"];
			$this->panel[$panel] = $panel;
			$this->tipo_panel[$panel]  = $rs["tipo_panel"];
			$this->objeto[$panel]=$rs["objeto"];
			$this->parametro[$panel]=$rs["parametro"];
			$this->modo[$panel]=$rs["modo"];
			
			//======================================
			if($i==1){
				$this->plantilla = $rs["plantilla"];
				$this->titulo = $rs["titulo"];
				$this->diagrama = $rs["diagrama"];
				$this->status = $rs["status"];
				$this->diagrama = leer_var($this->diagrama,$this->vform,C_IDENT_VAR_FORM,false);			
				$this->diagrama = leer_var($this->diagrama,$this->vses,C_IDENT_VAR_SES,false);
				$this->extraer_paneles($this->diagrama);
				$this->diagrama = preg_replace("/(?:--([0-9]+)--)/","~~$1~~",$this->diagrama);
				hr($this->diagrama);
			}// end if
		}// end while
		return $this->diagrama;
	}// end function
	//===========================================================
	function evaluar_todo($q=""){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,false);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,false);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var($q=""){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,false);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,false);
		return $q;
	}// end function
	//===========================================================
}// end class
?>