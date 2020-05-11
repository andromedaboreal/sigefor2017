<?php 
/*****************************************************************
creado: 15/08/2007
modificado: 21/08/2007
por: Yanny Nuñez
*****************************************************************/
require_once("cls_table.php");
require_once("cls_element_html.php");
require_once("cfg_item.php");


class cls_navegador{
	var $navegador = "";
	var $titulo = "";
	var $clase = "";
	var $plantilla = "";
	var $parametros = "";
	var $expresiones = "";
	var $estilo = "";
	var $propiedades = "";
	var $status = "";

	var $caption = "";
	var $width = "100%";
	var $border = "0";
	var	$cellspacing = "2";
	var	$cellpadding = "2";

	var $modo = C_ART_CONTENIDO;
	var $cfg_navegadores = C_CFG_NAVEGADORES;
	var $cfg_acciones = C_CFG_ACCIONES;
	var $cfg_nav_acc = C_CFG_NAV_ACC;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	var	$cfg_gpo_acc = C_CFG_GPO_ACC;
	var $cfg_gpo_usr = C_CFG_GPO_USR;


	var	$cfg_usr_acc = C_CFG_USR_ACC;
	var $cfg_gpo_nav_acc = C_CFG_GPO_NAV_ACC;



	var $id_boton_rep = "boton_rep";
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	//===========================================================
	function control($navegador_x=""){
		if($navegador_x!=""){
			$this->navegador = $navegador_x;
		}// end if
		//hr("navegador: ($this->navegador)","green");
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// end if
		$this->usuario = $this->vses["SS_USUARIO"];
		//===========================================================
		$cn = &$this->conexion;
		$cn->query = "	SELECT 
						b.navegador, b.titulo as titulo_navegador, b.clase as b_clase, b.tipo as tipo_navegador, b.plantilla, 
						b.parametros as b_parametros, b.expresiones, b.estilo as b_estilo, b.propiedades as b_propiedades,

						c.accion, c.titulo, c.panel, c.elemento, c.nombre, c.modo, c.registro, c.pagina, c.referencia, c.target, 
						c.proc_ini, c.proc_fin, c.cmd_ini, c.cmd_fin, c.interaccion, c.estructura, c.parametros,
						c.obj, c.obj_funcion, c.secuencia, c.nodo, c.funcion, c.sesion, 
						c.eventos, c.validar, c.confirmar, c.mensaje, 
						c.tipo, c.clase, c.estilo, c.propiedades, c.status, 						
						
						a.clase as a_clase, a.separador, a.orden, a.cmd_ini as a_cmd_ini, a.cmd_fin as a_cmd_fin, 
						a.proc_ini as a_proc_ini, a.proc_fin as a_proc_fin, a.parametros as a_parametros, a.expresiones as a_expresiones, 
						a.estilo as a_estilo, a.propiedades as a_propiedades
						
						FROM $this->cfg_nav_acc as a
						INNER JOIN $this->cfg_acciones as c ON a.accion = c.accion
						INNER JOIN $this->cfg_navegadores as b ON b.navegador = a.navegador
		 

						INNER JOIN $this->cfg_gpo_usr as g ON 1=1
						LEFT JOIN $this->cfg_gpo_acc as f ON f.accion = a.accion AND g.grupo = f.grupo
						LEFT JOIN $this->cfg_gpo_nav_acc as h ON h.navegador = a.navegador AND h.accion = a.accion AND h.grupo = g.grupo
						LEFT JOIN $this->cfg_usr_acc as e ON e.accion = a.accion AND e.usuario = g.usuario
		 
						WHERE a.navegador = '$this->navegador' AND g.usuario = '$this->usuario'
						AND b.status='1' AND c.status='1'
						AND (e.permitir=1 or e.accion IS NULL)
						AND (f.permitir=1 or f.accion IS NULL)
						AND (h.permitir=1 or h.accion IS NULL)
						
						GROUP BY a.accion
						ORDER BY a.orden	
							";
						//hr($cn->query);
		$result = $cn->ejecutar();
		$i=0;

		if($this->vses["DEBUG"] == "1"){
		
			$this->deb->dbg($this->panel,$this->navegador,$this->titulo,"navegador=$this->navegador","n");
		}// end if



		//===========================================================
		if($rs = $cn->consultar($result)){
			$this->vpara = $rs;
			$this->navegador = $rs["navegador"];
			$this->titulo = $rs["titulo_navegador"];
			if($rs["b_clase"]!=""){
				$this->clase = $rs["b_clase"];
			}// end if
			$this->tipo = $rs["tipo_navegador"];
			$this->plantilla = $rs["plantilla"];
			$this->parametros = $rs["b_parametros"];
			$this->expresiones = $rs["expresiones"];
			$this->estilo = $rs["b_estilo"];
			$this->propiedades = $rs["b_propiedades"];
			$this->parametros = reparar_sep($this->evaluar_todo($this->parametros));
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			$this->expresiones = reparar_sep($this->evaluar_todo($this->expresiones));
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = array_merge($this->vexp,$prop);
			}// end if
			//===========================================================
			
			if($this->clase != ""){
				if($this->clase_navegador == ""){
					$this->clase_navegador = $this->clase."_navegador";
				}// end if 
				if($this->clase_regla == ""){
					$this->clase_regla = $this->clase."_nav_regla";
				}// end if 
				if($this->clase_item == ""){
					$this->clase_item = $this->clase."_nav_item";
				}// end if 
			}// end if
			//===========================================================
			$this->estilo = reparar_sep($this->evaluar_todo($this->estilo));
			$this->propiedades = reparar_sep($this->evaluar_todo($this->propiedades));
			//===========================================================#fff5ee;color: #8b008b;

			do{
				
				if($rs["clase"]==""){
					if($rs["a_clase"]!=""){
						$rs["clase"] = $rs["a_clase"];
					}else{
						$rs["clase"] = $this->clase;
					}// end if
				}// end if
				

				if($rs["proc_ini"] == ""){
					$rs["proc_ini"] = $rs["a_proc_ini"];
				}// end if
				if($rs["proc_fin"] == ""){
					$rs["proc_fin"] = $rs["a_proc_fin"];
				}// end if
				if($rs["cmd_ini"] == ""){
					$rs["cmd_ini"] = $rs["a_cmd_ini"];
				}// end if
				if($rs["cmd_fin"] == ""){
					$rs["cmd_fin"] = $rs["a_cmd_fin"];
				}// end if
				

				$rs["expresiones"] = $rs["a_expresiones"];
				$rs["parametros"] = reparar_sep($rs["a_parametros"]).$rs["parametros"];
				$rs["estilo"] = $this->estilo.reparar_sep($rs["a_estilo"]).$rs["estilo"];
				$rs["propiedades"] = $this->propiedades.reparar_sep($rs["a_propiedades"]).$rs["propiedades"];
				//===========================================================
				
				$item[$i] = new cfg_item;
				$item[$i]->vses = &$this->vses;
				$item[$i]->vform = &$this->vform;
				$item[$i]->vexp = &$this->vexp;
				$item[$i]->deb = &$this->deb;
				
				$item[$i]->separador = $rs["separador"];
				$item[$i]->orden = $rs["orden"];
				$item[$i]->tipo = $rs["tipo"];

$item[$i]->smenu_posicion = 1;
$item[$i]->menu_orientacion==C_MENU_HORIZONTAL;


$item[$i]->panel_default = $this->panel_default;
$item[$i]->panel_actual = $this->panel;
if($this->panel_submit != ""){
	$item[$i]->panel_submit = $this->panel_submit;
}// end if
//hr($item[$i]->panel_actual);
				$item[$i]->ejecutar($rs);
				//===========================================================
				if($item[$i]->clase != ""){
					if($item[$i]->clase_item == ""){
						$item[$i]->clase_item = $item[$i]->clase."_nav_item";
					}// end if
					if($item[$i]->clase_regla == ""){
						$item[$i]->clase_regla = $item[$i]->clase."_nav_regla";
					}// end if
				}// end if
				if($item[$i]->clase_item == ""){
					$item[$i]->clase_item = $this->clase_item;
				}// end if
				if($item[$i]->clase_regla==""){
					$item[$i]->clase_regla = $this->clase_regla;
				}// end if 
				//===========================================================
				$this->item[$i] = &$item[$i];
				
				$i++;$this->nro_items = $i;
			}while ($rs = $cn->consultar($result));
		}// end if
		//===========================================================
		switch ($this->tipo){
		case C_NAV_PATRON:
			return $this->nav_patron();
			break;
		case C_NAV_NORMAL:
		default:
			return $this->nav_normal();
			break;
		}// end switch
		//===========================================================
	}// end fucntion
	//===========================================================
	function nav_normal(){
		$t = new cls_table(1,1);
		$t->caption->text = $this->caption;
		$t->width = $this->width;
		$t->border = $this->border;
		$t->cellspacing = $this->cellspacing;
		$t->cellpadding = $this->cellpadding;
		$t->class = $this->clase_navegador;
		//===========================================================
		$aux = "";
		for($i=0;$i<$this->nro_items;$i++){
	
			$separador= "";
			$item = $this->item[$i];
			if($item->oculto=="si"){
				continue;
			}// end if
			switch ($this->item[$i]->separador){
			case C_NAV_SEP_ESPACIO:
				$separador = "&nbsp;";
				break;			
			case C_NAV_SEP_DBLESPACIO:
				$separador = "&nbsp;&nbsp;";
				break;			
			case C_NAV_SEP_TRIESPACIO:
				$separador = "&nbsp;&nbsp;&nbsp;";
				break;			
			case C_NAV_SEP_REGLA:
				if($item->clase_regla!=""){
					$separador = "<hr class=\"$item->clase_regla\">";
				}else{
					$separador = "<hr/>";
				}// end if
				break;			
			case C_NAV_SEP_LINEA:
				$separador = "<br>";
				break;
			case C_NAV_SEP_DBLLINEA:
				$separador = "<br><br>";
				break;
			case C_NAV_SEP_HTML:
				$separador = $item->separador_html;
				break;
			case C_NAV_SEP_NINGUNO:
			default:
				$separador = "";
				break;
			}// end switch
			//===========================================================
			switch($item->tipo){
			case "1":
				$tipo_x = "submit";
				break;
			case "2":
				$tipo_x = "button";
				break;
			case "3":
				$tipo_x = "reset";
				break;
			case "4":
				$tipo_x = "a";
				break;
			
			default:
				$tipo_x = "submit";
				break;
			}// end switch
			if($item->tipo!="4"){
				$boton = new cls_element_html($tipo_x,"btn_".$item->accion."_aux");
				$boton->value = $item->titulo;
			
			}else{
			
				$boton = new cls_element_html($tipo_x,"btn_".$item->accion."_aux");
				$boton->inner_html = $item->titulo;
				
			
			}// end if
			$boton->class = $item->clase_item;
			if($item->estilo != ""){
				$boton->style = $item->estilo;
			}// end if
			if($prop = extraer_para($item->propiedades)){
				foreach($prop as $para => $valor){
					eval("\$boton->$para = \"$valor\";");
				}// next
			}// end if
			if($prop = extraer_para($this->evaluar_exp($item->eventos))){
				foreach($prop as $para => $valor){
					eval("\$boton->$para .= \"$valor\";");
				}// next
			}// end if
			
			$aux .= $separador.$boton->control();
		}// next
		$t->cell[0][0]->text = $aux;
		$t->cell[0][0]->class = $this->clase_navegador;
		if($this->estilo != ""){
			$t->cell[0][0]->style = $this->estilo;
		}// end if
		if($prop = extraer_para($this->propiedades)){
			foreach($prop as $para => $valor){
				eval("\$t->cell[0][0]->$para = \"$valor\";");
			}// next
		}// end if

		if($item->abrir_menu!=""){
			$t->cell[$f][$c]->onclick = $item->abrir_menu;
		}// end if
		
		
		return $t->control();
		//===========================================================
	}// end function

	//===========================================================
	function nav_patron(){
		if(!$this->diagrama = $this->leer_diagrama($this->plantilla)){
			return "";
		}// end if
		$this->diagrama = $this->evaluar_var($this->diagrama);
		//===========================================================
		$this->diagrama = str_replace("--class--",$this->clase_navegador,$this->diagrama);		
		$this->diagrama = str_replace("--width--",$this->width,$this->diagrama);
		$this->diagrama = str_replace("--border--",$this->border,$this->diagrama);
		$this->diagrama = str_replace("--cellspacing--",$this->cellspacing,$this->diagrama);
		$this->diagrama = str_replace("--cellpadding--",$this->cellpadding,$this->diagrama);
		//===========================================================
		$this->boton_rep = extraer_patron($this->diagrama,$this->id_boton_rep);
		$this->fila_rep = extraer_patron($this->diagrama,$this->id_fila);
		$this->columna_rep = extraer_patron($this->diagrama,$this->id_columna);	
		//===========================================================


		$aux = "";
		for($i=0;$i<$this->nro_items;$i++){
			
			
			$item = $this->item[$i];
			$separador= "";
			if($item->oculto=="si"){
				continue;
			}// end if
			switch ($this->item[$i]->separador){
			case C_NAV_SEP_ESPACIO:
				$separador = "&nbsp;";
				break;			
			case C_NAV_SEP_DBLESPACIO:
				$separador = "&nbsp;&nbsp;";
				break;			
			case C_NAV_SEP_TRIESPACIO:
				$separador = "&nbsp;&nbsp;&nbsp;";
				break;			
			case C_NAV_SEP_REGLA:
				if($item->clase_regla!=""){
					$separador = "<hr class=\"$item->clase_regla\">";
				}else{
					$separador = "<hr/>";
				}// end if
				break;			
			case C_NAV_SEP_LINEA:
				$separador = "<br>";
				break;
			case C_NAV_SEP_DBLLINEA:
				$separador = "<br><br>";
				break;
			case C_NAV_SEP_HTML:
				$separador = $item->seperador_html;
				break;
					
			default:
				$separador = "";
				break;
			}// end switch
			//===========================================================
			$boton = $this->boton_rep;

			$boton = str_replace("--titulo--",$item->titulo,$boton);
			$boton = str_replace("--clase--",$item->clase_item,$boton);
			$boton = str_replace("--propiedades--",$item->propiedades,$boton);
			$boton = str_replace("--estilo--",$item->estilo,$boton);
			$eventos_x = "";
			if($prop = extraer_para($this->evaluar_exp($item->eventos))){
				foreach($prop as $para => $valor){
					$eventos_x .= " $para=\"$valor\"";
				}// next
			}// end if
			
			$boton = str_replace("--eventos--",$eventos_x,$boton);
			$aux .= $separador.$boton;
		}// next
		$this->diagrama = formar_diagrama($this->diagrama,$this->id_boton_rep,$aux);
		
		return $this->diagrama;




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