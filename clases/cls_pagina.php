<?php 
/*****************************************************************
creado: 04/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
//require_once("cls_mysql.php");
//require_once("cls_articulo.php");
//require_once("cls_element_html.php");
define("C_PAG_ESTATICO","0");
define("C_PAG_DINAMICO","1");
class cls_pagina{
	var $pagina = "";
	var $titulo = "";
	var $plantilla = "";
	var $rotatorio = C_PAG_ESTATICO;
	var $parametros = "";
	var $expresiones = "";
	var $status = "";
	var $cfg_paginas = C_CFG_PAGINAS;
	var $cfg_pag_art = C_CFG_PAG_ART;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	var $script = "";
	//===========================================================
	function control($pagina_x=""){
		if($pagina_x!=""){
			$this->pagina = $pagina_x;
		}// end if
		$cn = new cls_conexion;//tiempo,
		$cn->query = " 	SELECT $this->cfg_pag_art.pagina, articulo, posicion, orden, 
						$this->cfg_paginas.pagina, $this->cfg_paginas.titulo, $this->cfg_paginas.plantilla, rotatorio, 
						$this->cfg_paginas.parametros, $this->cfg_paginas.expresiones, $this->cfg_paginas.status, 
						$this->cfg_plantillas.diagrama
						FROM $this->cfg_pag_art 
						INNER JOIN $this->cfg_paginas ON $this->cfg_paginas.pagina = $this->cfg_pag_art.pagina
						INNER JOIN $this->cfg_plantillas ON $this->cfg_plantillas.plantilla = $this->cfg_paginas.plantilla
						WHERE $this->cfg_pag_art.pagina = '$this->pagina' AND  $this->cfg_paginas.status = '1' 
							AND $this->cfg_plantillas.status = '1'
						ORDER BY orden";
		$result = $cn->ejecutar();
		if($rs = $cn->consultar($result)){
			$this->vpara = &$rs;
			$this->pagina = $rs["pagina"];
			$this->titulo = $rs["titulo"];
			$this->plantilla = $rs["plantilla"];
			$this->rotatorio = $rs["rotatorio"];
			$this->parametros = $rs["parametros"];
			$this->expresiones = $rs["expresiones"];
			$this->status = $rs["status"];
			$this->diagrama = $rs["diagrama"];
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
				$this->vexp = $prop;
			}// end if
			//===========================================================
			$this->diagrama = $this->evaluar_var($this->diagrama);
			$art = new cls_articulo;
			$aux = "";
			do{
				$this->articulo = $rs["articulo"];
				$this->posicion = $rs["posicion"];
				$this->orden = $rs["orden"];
				$this->tiempo = $rs["tiempo"];
				if($pos[$this->posicion]=="1" and ($this->rotatorio=="si" or $this->rotatorio==true)){
					$aux2 = "$this->posicion:$this->orden:".$art->control($this->articulo,1);
					$aux2 = str_replace(chr(10),"",$aux2);
					$aux2 = str_replace(chr(13)," ",$aux2);
					$aux .= "\nx12=\"".addslashes($aux2)."\"";
				}else if($pos[$this->posicion]!="1"){
					$pos[$this->posicion] = "1";
					$span = new cls_element_html("span","div_$this->posicion");
					$span->inner_html = $art->control($this->articulo,1);
					$this->diagrama = str_replace("--$this->posicion--",$span->control(),$this->diagrama);
				}// end if
			}while($rs = $cn->consultar($result));
			$this->script = $aux;		
			return $this->diagrama;
		}else{
			return "";
		}// end if
	}//end function
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
}// end class
?>