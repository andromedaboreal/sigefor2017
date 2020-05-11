<?php
/*****************************************************************
creado: 23/04/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
//===========================================================



class cls_menu{
	var $menu = "";
	var $menu_titulo = "";
	var $clase = "";
	var $plantilla = "";
	var $parametros = "";
	var $expresiones = "";
	var $estilo = "";
	var $propiedades = "";
	var $estilo_titulo = "";
	var $propiedades_titulo = "";
	var $estilo_item = "";
	var $propiedades_item = "";
	var $status = "";
	//===========================================================
	var $tipo = 1;//C_MENU_NORMAL;
	var $imagen = C_MENU_IMG_TXT_DERECHA;
	var $panel = "";
	var $orden = "";
	var $orientacion = "";
	var $diagrama = "";
	var $indicador_img = C_MENU_IMG_IND;
	//===========================================================
	var $id = "";
	var $conexion = false;
	var $item = array();
	var $nro_items = 0;
	var $delta = "";
	var $filas = "0";
	var $columnas = "3";
	var $nItem = 0;
	//===========================================================
	var $clase_menu = "";
	var $clase_titulo = "";
	var $clase_item = "";
	var $clase_over = "";
	var $clase_disabled = "";
	var $clase_icono = "";
	//===========================================================
	var $sin_titulo = "no";
	var $sin_efecto = "no";
	var $ancho_libre = "no";
	//===========================================================
	var $width = "100%";
	var $border = "0";
	var $cellspacing = "2";
	var $cellpadding = "2";
	var $ancho_columna = "";

	var $imagen_alto = "";
	var $imagen_ancho = "";
	var $imagen_align = "";
	//===========================================================
	var	$cfg_acciones = C_CFG_ACCIONES;
	var	$cfg_men_acc = C_CFG_MEN_ACC;
	var	$cfg_menus = C_CFG_MENUS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	var	$cfg_usr_acc = C_CFG_USR_ACC;
	var	$cfg_gpo_acc = C_CFG_GPO_ACC;
	var $cfg_gpo_usr = C_CFG_GPO_USR;
	var $cfg_gpo_men_acc = C_CFG_GPO_MEN_ACC;
	//===========================================================
	var $id_fila = "fila_rep";
	var $id_columna = "columna_rep";
	var $id_fila_titulo = "fila_titulo";
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	
	public $modo_async = false;
	private $_index = 0;
	//===========================================================
	function __construct($menu_x="",$id_x=""){
		if($menu_x!=""){
			$this->menu = $menu_x;
		}// end if
		if($id_x!=""){
			$this->id = $id_x;
		}// end if
		$this->conexion = sgConnection();
	}// end function
	//===========================================================
	function control($menu_x="",$usuario_x=""){
		if($menu_x!=""){
			$this->menu = $menu_x;
		}// end if
		if($usuario_x!=""){
			$this->usuario = $usuario_x;
		}// end if
		//===========================================================
		
		$cn = &$this->conexion;
		
		$options = array();
		
		$cn->query = "	SELECT *

						/*c.titulo, c.clase, c.plantilla,c.parametros,
						c.expresiones, c.estilo,c.propiedades,
						c.estilo_titulo, c.propiedades_titulo, c.estilo_item, c.propiedades_item*/

						FROM $this->cfg_menus as c 
						WHERE c.menu = '$this->menu' 
						
						";	
						
		$result = $cn->ejecutar($cn->query);
		
		$this->options = array();
		if($rs = $cn->consultar_asoc($result)){
			$this->vpara = &$rs;
			$this->nombre = $rs["menu"];
			$this->titulo = $rs["titulo"];
			
			$this->clase = $rs["clase"];
			
			$this->plantilla =  $rs["plantilla"];
			$this->parametros .= reparar_sep($rs["parametros"]);
			$this->expresiones = reparar_sep($rs["expresiones"]);
			$this->estilo .= reparar_sep($rs["estilo"]);
			$this->propiedades .= reparar_sep($rs["propiedades"]);
			$this->estilo_titulo .= reparar_sep($rs["estilo_titulo"]);
			$this->propiedades_titulo .= reparar_sep($rs["propiedades_titulo"]);
			$this->estilo_item .= reparar_sep($rs["estilo_item"]);
			$this->propiedades_item .= reparar_sep($rs["propiedades_item"]);
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				foreach($prop as $para => $valor){
					$this->$para = $valor;
				}// next
				$prop["titulo"] = $rs["titulo"];
				$this->vpara = array_merge($this->vpara, $prop);
			}// end if
			//===========================================================
			$this->expresiones = reparar_sep($this->evaluar_todo($this->expresiones));
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = array_merge($this->vexp, $prop);
			}// end if
			//===========================================================
			$this->estilo = $this->evaluar_todo($this->estilo);
			$this->propiedades = $this->evaluar_todo($this->propiedades);
			$this->estilo_titulo = $this->estilo.$this->evaluar_todo($this->estilo_titulo);
			$this->propiedades_titulo = $this->propiedades.$this->evaluar_todo($this->propiedades_titulo);
			$this->estilo_item = $this->estilo.$this->evaluar_todo($this->estilo_item);
			$this->propiedades_item = $this->propiedades.$this->evaluar_todo($this->propiedades_item);
			//===========================================================
			
			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg($this->panel,$this->nombre,$this->titulo,"menu=$this->nombre","m");
			}// end if
			
			
			$this->loadItems($this->menu, 0,  false);
			
			/*
			$cn->query = "	
				SELECT 
					a.accion, a.titulo, a.panel, a.elemento, a.nombre, a.modo, a.registro, a.pagina, a.referencia, a.target, 
					a.proc_ini, a.proc_fin, a.cmd_ini, a.cmd_fin, a.interaccion, a.estructura, a.parametros,
					a.obj, a.obj_funcion, a.secuencia, a.nodo, a.funcion, a.sesion, 
					a.eventos, a.validar, a.confirmar, a.mensaje, 
					a.tipo, a.clase, a.estilo, a.propiedades, a.status, 						

					b.menu, b.accion, b.clase as clase_b, b.orden, 
					b.cmd_ini as cmd_ini_b, b.cmd_fin as cmd_fin_b, b.proc_ini as proc_ini_b, b.proc_fin as proc_fin_b,
					b.parametros as parametros_b, b.expresiones, b.estilo as estilo_b, b.propiedades as propiedades_b

				FROM $this->cfg_men_acc as b

				INNER JOIN $this->cfg_acciones as a ON b.accion = a.accion
				INNER JOIN $this->cfg_gpo_usr as g ON 1=1
				LEFT JOIN $this->cfg_gpo_acc as f ON f.accion = b.accion AND g.grupo = f.grupo
				LEFT JOIN $this->cfg_gpo_men_acc as h ON h.menu = b.menu AND h.accion = b.accion AND h.grupo = g.grupo
				LEFT JOIN $this->cfg_usr_acc as e ON e.accion = b.accion AND e.usuario = g.usuario
				WHERE b.menu = '$this->menu' AND g.usuario = '$this->usuario'
				AND (e.permitir=1 or e.accion IS NULL)
				AND (f.permitir=1 or f.accion IS NULL)
				AND (h.permitir=1 or h.accion IS NULL)
				GROUP BY b.accion
				ORDER BY b.orden";	
				//hr($cn->query);
			$result = $cn->ejecutar($cn->query);
			$i = 0;			
			while($rs = $cn->consultar($result)){
				
				$rs["parametros"] = reparar_sep($rs["parametros_b"]).reparar_sep($rs["parametros"]);
				$rs["estilo"] = $this->estilo_item.reparar_sep($rs["estilo_b"]).reparar_sep($rs["estilo"]);
				$rs["propiedades"] = $this->propiedades_item.reparar_sep($rs["propiedades_b"]).reparar_sep($rs["propiedades"]);
				if($rs["clase"]==""){
					$rs["clase"] = $rs["clase_b"];
				}// end if
				if($rs["clase"]==""){
					$rs["clase"] = $this->clase;
				}// end if
				if($rs["proc_ini"] == ""){
					$rs["proc_ini"] = $rs["proc_ini_b"];
				}// end if
				if($rs["proc_fin"] == ""){
					$rs["proc_fin"] = $rs["proc_fin_b"];
				}// end if
				if($rs["cmd_ini"] == ""){
					$rs["cmd_ini"] = $rs["cmd_ini_b"];
				}// end if
				if($rs["cmd_fin"] == ""){
					$rs["cmd_fin"] = $rs["cmd_fin_b"];
				}// end if
				//===========================================================
				$this->item[$i] = new cfg_item();
				$this->item[$i]->vses = &$this->vses;
				$this->item[$i]->vform = &$this->vform;
				$this->item[$i]->vexp = &$this->vexp;
				$this->item[$i]->deb = &$this->deb;
				$this->item[$i]->menu_orientacion = $this->orientacion;
				$this->item[$i]->smenu_posicion = $this->posicion;
				$this->item[$i]->panel_default = $this->panel_default;
				$this->item[$i]->panel_actual = $this->panel;
				
				
				$this->item[$i]->ejecutar($rs);

				
				
				

				if($this->item[$i]->menuest == "si"){
					$r = $this->vses["menu_nivel"];
					$nro_menuest = count($r);
					$j = 0;
					foreach($r as $k => $v){
						$j++;
						$rss = $rs;
						$rss["titulo"] = "&raquo; ".$this->vses["menut_nivel"][$k];
						$rss["estructura"]=$v;	
						$this->item[$i] = new cfg_item();
						$this->item[$i]->vses = &$this->vses;
						$this->item[$i]->vform = &$this->vform;
						$this->item[$i]->vexp = &$this->vexp;
						$this->item[$i]->deb = &$this->deb;
						$this->item[$i]->menu_orientacion = $this->orientacion;
						$this->item[$i]->smenu_posicion = $this->posicion;
						$this->item[$i]->panel_default = $this->panel_default;
						$this->item[$i]->panel_actual = $this->panel;
						$this->item[$i]->restablecer_est = "si";

						if($nro_menuest == $j){
							$this->item[$i]->deshabilitado = true;
						}// end if

						
						$this->item[$i]->ejecutar($rss);
						
						$item = new stdClass;
				
						$item->id = $i;
						$item->parentId = false;
						$item->text = utf8_encode($this->item[$i]->titulo);

						$item->checked = false;
						$item->icon = "";
						$item->wCheck = false;

						$item->events = array("click" => $this->item[$i]->even["onclick"]);
						
						if($nro_menuest == $j){
							$item->disabled = true;
						}// end if
						
						
						$options[] = $item;
						
						
						$i++;
					}// next
					
					$i--;
					
				}else{
					$item = new stdClass;
				
					$item->id = $i;
					$item->parentId = false;
					$item->text = utf8_encode($this->item[$i]->titulo);

					$item->checked = false;
					$item->icon = "";
					$item->wCheck = false;

					$item->events = array("click" => $this->item[$i]->even["onclick"]);

					$options[] = $item;
					
					if($this->item[$i]->elemento == C_OBJ_MENU){
						
					}
					
					
				}// end if

				if($this->item[$i]->oculto=="si"){
					continue;
				}// end if
				
				$i++;				
				
			}// wend
			*/
		}
		
		
		return $this->sgMenu($this->menu."_".$this->panel, $this->options);	

		//===========================================================				
		$i=0;
		if($rs = $cn->consultar($result)){
			$this->vpara = &$rs;
			$this->nombre = $rs["menu"];
			$this->titulo = $rs["menu_titulo"];
			if($rs["menu_clase"]!=""){
				$this->clase = $rs["menu_clase"];
			}// end if
			$this->plantilla =  $rs["menu_plantilla"];
			$this->parametros .= reparar_sep($rs["menu_parametros"]);
			$this->expresiones = reparar_sep($rs["menu_expresiones"]);
			$this->estilo .= reparar_sep($rs["menu_estilo"]);
			$this->propiedades .= reparar_sep($rs["menu_propiedades"]);
			$this->estilo_titulo .= reparar_sep($rs["estilo_titulo"]);
			$this->propiedades_titulo .= reparar_sep($rs["propiedades_titulo"]);
			$this->estilo_item .= reparar_sep($rs["estilo_item"]);
			$this->propiedades_item .= reparar_sep($rs["propiedades_item"]);
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
				$prop["titulo"] = $rs["titulo"];
				$this->vpara = array_merge($this->vpara,$prop);
			}// end if
			//===========================================================
			$this->expresiones = reparar_sep($this->evaluar_todo($this->expresiones));
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = array_merge($this->vexp, $prop);
			}// end if
			//===========================================================
			$this->estilo = $this->evaluar_todo($this->estilo);
			$this->propiedades = $this->evaluar_todo($this->propiedades);
			$this->estilo_titulo = $this->estilo.$this->evaluar_todo($this->estilo_titulo);
			$this->propiedades_titulo = $this->propiedades.$this->evaluar_todo($this->propiedades_titulo);
			$this->estilo_item = $this->estilo.$this->evaluar_todo($this->estilo_item);
			$this->propiedades_item = $this->propiedades.$this->evaluar_todo($this->propiedades_item);
			//===========================================================
			
			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg($this->panel,$this->nombre,$this->titulo,"menu=$this->nombre","m");
			}// end if
			
			
			
			do{
				$rs["parametros"] = reparar_sep($rs["parametros_b"]).reparar_sep($rs["parametros"]);
				$rs["estilo"] = $this->estilo_item.reparar_sep($rs["estilo_b"]).reparar_sep($rs["estilo"]);
				$rs["propiedades"] = $this->propiedades_item.reparar_sep($rs["propiedades_b"]).reparar_sep($rs["propiedades"]);
				if($rs["clase"]==""){
					$rs["clase"] = $rs["clase_b"];
				}// end if
				if($rs["clase"]==""){
					$rs["clase"] = $this->clase;
				}// end if
				if($rs["proc_ini"] == ""){
					$rs["proc_ini"] = $rs["proc_ini_b"];
				}// end if
				if($rs["proc_fin"] == ""){
					$rs["proc_fin"] = $rs["proc_fin_b"];
				}// end if
				if($rs["cmd_ini"] == ""){
					$rs["cmd_ini"] = $rs["cmd_ini_b"];
				}// end if
				if($rs["cmd_fin"] == ""){
					$rs["cmd_fin"] = $rs["cmd_fin_b"];
				}// end if
				//===========================================================
				$this->item[$i] = new cfg_item();
				$this->item[$i]->vses = &$this->vses;
				$this->item[$i]->vform = &$this->vform;
				$this->item[$i]->vexp = &$this->vexp;
				$this->item[$i]->deb = &$this->deb;
				$this->item[$i]->menu_orientacion = $this->orientacion;
				$this->item[$i]->smenu_posicion = $this->posicion;
				$this->item[$i]->panel_default = $this->panel_default;
				$this->item[$i]->panel_actual = $this->panel;
				
				
				$this->item[$i]->ejecutar($rs);


				if($this->item[$i]->menuest == "si"){
					$r = $this->vses["menu_nivel"];
					$nro_menuest = count($r);
					$j = 0;
					foreach($r as $k => $v){
						$j++;
						$rss = $rs;
						$rss["titulo"] = "&raquo; ".$this->vses["menut_nivel"][$k];
						$rss["estructura"]=$v;	
						$this->item[$i] = new cfg_item();
						$this->item[$i]->vses = &$this->vses;
						$this->item[$i]->vform = &$this->vform;
						$this->item[$i]->vexp = &$this->vexp;
						$this->item[$i]->deb = &$this->deb;
						$this->item[$i]->menu_orientacion = $this->orientacion;
						$this->item[$i]->smenu_posicion = $this->posicion;
						$this->item[$i]->panel_default = $this->panel_default;
						$this->item[$i]->panel_actual = $this->panel;
						$this->item[$i]->restablecer_est = "si";

						if($nro_menuest == $j){
							$this->item[$i]->deshabilitado = true;
						}// end if

						
						$this->item[$i]->ejecutar($rss);
						$i++;
					}// next
					
					$i--;
					
					
					
				}// end if

				if($this->item[$i]->oculto=="si"){
					continue;
				}// end if
				
				$i++;
			}while ($rs = $cn->consultar($result));
			$this->nro_items = $i++;
		}else{
			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg($this->panel,$this->menu,$this->menu,"menu=$this->menu","m");
			}// end if
		}// end if
		//===========================================================
		if($this->clase != ""){
			if($this->clase_menu == ""){
				$this->clase_menu = $this->clase."_men";		
			}//end if
			if($this->clase_titulo == ""){
				$this->clase_titulo = $this->clase."_men_titulo";		
			}//end if
			if($this->clase_item == ""){
				$this->clase_item = $this->clase."_men_itm";
			}//end if
			if($this->clase_over == ""){
				$this->clase_over = $this->clase."_men_itm";
			}//end if
			if($this->clase_disabled == ""){
				$this->clase_disabled = $this->clase."_men_itm_disabled";
			}//end if
			if($this->clase_icono == ""){
				$this->clase_icono = $this->clase."_men_itm_icono";
			}//end if
		}// end if
		//===========================================================
		if ($this->sin_titulo == "si"){
			$this->delta = 0;
			$this->delta2 = 0;
		}else{
			if($this->titulo_lateral=="si"){
				$this->delta = 0;
				$this->delta2 = 1;
			}else{
				$this->delta = 1;
				$this->delta2 = 0;
			}// end if			
		}// end if
		
		
		//===========================================================
		switch ($this->orientacion){
		case C_MENU_VERTICAL:
			$this->columnas = 1;
			$this->filas = $this->nro_items;
			break;
		case C_MENU_HORIZONTAL:
			$this->filas = 1;
			$this->columnas = $this->nro_items;
			break;
		case C_MENU_BIDIMENSIONAL:
			if($this->filas == "0" and $this->columnas != "0"){
				if($this->columnas > $this->nro_items){
					$this->columnas = $this->nro_items;
				}// end if
				$this->filas = ceil($this->nro_items/$this->columnas);	
			}else if ($this->filas != "0" and $this->columnas == "0"){
				$this->columnas = ceil($this->nro_items/$this->filas);
				if(ceil($this->nro_items/ $this->columnas)<$this->filas){
					$this->filas = $this->nro_items/ $this->columnas;
				}// end if
			}// end if
			break;
		case C_MENU_SUBMENU:
			$this->crear_script();
			return true;
			break;
		}// end switch
		$this->filas += $this->delta;
		$this->columnas += $this->delta2;
		switch ($this->tipo){
		case C_MENU_PATRON:
			return $this->menu_patron();
			break;
		case C_MENU_DISENO:
			return $this->menu_patron();
			break;
		case C_MENU_ARCHIVO:
			return $this->menu_patron();
			break;
		case C_MENU_NORMAL:
		default:
			//$this->crear_script();
			return $this->menu_normal();
			
			break;

	
		
		}// end switch
		
		//;
		
	}// end function
	//===========================================================
	
	private function loadItems($menu, $i, $parent = false){
		
		
		
		$cn = &$this->conexion;
		$cn->query = "	
				SELECT DISTINCT
					a.accion, a.titulo, a.panel, a.elemento, a.nombre, a.modo, a.registro, a.pagina, a.referencia, a.target, 
					a.proc_ini, a.proc_fin, a.cmd_ini, a.cmd_fin, a.interaccion, a.estructura, a.parametros,
					a.obj, a.obj_funcion, a.secuencia, a.nodo, a.funcion, a.sesion, 
					a.eventos, a.validar, a.confirmar, a.mensaje, 
					a.tipo, a.clase, a.estilo, a.propiedades, a.status, 						

					b.menu, b.accion, b.clase as clase_b, b.orden, 
					b.cmd_ini as cmd_ini_b, b.cmd_fin as cmd_fin_b, b.proc_ini as proc_ini_b, b.proc_fin as proc_fin_b,
					b.parametros as parametros_b, b.expresiones, b.estilo as estilo_b, b.propiedades as propiedades_b

				FROM $this->cfg_men_acc as b

				INNER JOIN $this->cfg_acciones as a ON b.accion = a.accion
				INNER JOIN $this->cfg_gpo_usr as g ON 1=1
				LEFT JOIN $this->cfg_gpo_acc as f ON f.accion = b.accion AND g.grupo = f.grupo
				LEFT JOIN $this->cfg_gpo_men_acc as h ON h.menu = b.menu AND h.accion = b.accion AND h.grupo = g.grupo
				LEFT JOIN $this->cfg_usr_acc as e ON e.accion = b.accion AND e.usuario = g.usuario
				WHERE b.menu = '$menu' AND g.usuario = '$this->usuario'
				AND (e.permitir=1 or e.accion IS NULL)
				AND (f.permitir=1 or f.accion IS NULL)
				AND (h.permitir=1 or h.accion IS NULL)
				
				ORDER BY b.orden";	
				//hr($cn->query);
		$result = $cn->ejecutar($cn->query);

		while($rs = $cn->consultar_asoc($result)){

			$rs["parametros"] = reparar_sep($rs["parametros_b"]).reparar_sep($rs["parametros"]);
			$rs["estilo"] = $this->estilo_item.reparar_sep($rs["estilo_b"]).reparar_sep($rs["estilo"]);
			$rs["propiedades"] = $this->propiedades_item.reparar_sep($rs["propiedades_b"]).reparar_sep($rs["propiedades"]);
			if($rs["clase"]==""){
				$rs["clase"] = $rs["clase_b"];
			}// end if
			if($rs["clase"]==""){
				$rs["clase"] = $this->clase;
			}// end if
			if($rs["proc_ini"] == ""){
				$rs["proc_ini"] = $rs["proc_ini_b"];
			}// end if
			if($rs["proc_fin"] == ""){
				$rs["proc_fin"] = $rs["proc_fin_b"];
			}// end if
			if($rs["cmd_ini"] == ""){
				$rs["cmd_ini"] = $rs["cmd_ini_b"];
			}// end if
			if($rs["cmd_fin"] == ""){
				$rs["cmd_fin"] = $rs["cmd_fin_b"];
			}// end if
			//===========================================================
			$this->item[$i] = new cfg_item();
			
			$this->item[$i]->ajax = $this->modo_async;
			
			$this->item[$i]->vses = &$this->vses;
			$this->item[$i]->vform = &$this->vform;
			$this->item[$i]->vexp = &$this->vexp;
			$this->item[$i]->deb = &$this->deb;
			$this->item[$i]->menu_orientacion = $this->orientacion;
			$this->item[$i]->smenu_posicion = $this->posicion;
			$this->item[$i]->panel_default = $this->panel_default;
			$this->item[$i]->panel_actual = $this->panel;


			$this->item[$i]->ejecutar($rs);

			if($this->item[$i]->oculto=="si"){
				$i++;
				continue;
			}// end if



			if($this->item[$i]->menuest == "si"){
				$r = $this->vses["menu_nivel"];
				$nro_menuest = count($r);
				$j = 0;
				foreach($r as $k => $v){
					$j++;
					$rss = $rs;
					$rss["titulo"] = "&raquo; ".$this->vses["menut_nivel"][$k];
					$rss["estructura"]=$v;	
					$this->item[$i] = new cfg_item();
					$this->item[$i]->vses = &$this->vses;
					$this->item[$i]->vform = &$this->vform;
					$this->item[$i]->vexp = &$this->vexp;
					$this->item[$i]->deb = &$this->deb;
					$this->item[$i]->menu_orientacion = $this->orientacion;
					$this->item[$i]->smenu_posicion = $this->posicion;
					$this->item[$i]->panel_default = $this->panel_default;
					$this->item[$i]->panel_actual = $this->panel;
					$this->item[$i]->restablecer_est = "si";

					if($nro_menuest == $j){
						$this->item[$i]->deshabilitado = true;
					}// end if


					$this->item[$i]->ejecutar($rss);

					$item = new stdClass;

					$item->index = $i;
					$item->parent = $parent;
					$item->caption = $this->item[$i]->titulo;

					$item->checked = false;
					$item->icon = "";
					$item->wCheck = false;

					$item->events = array("click" => $this->item[$i]->even["onclick"]);

					if($nro_menuest == $j){
						$item->disabled = true;
					}// end if


					$this->options[] = $item;


					$i++;
				}// next

				$i--;

			}else{
				$item = new stdClass;

				$item->index = $i;
				$item->parent = $parent;
				$item->caption = $this->item[$i]->titulo;

				$item->checked = false;
				$item->icon = "";
				$item->wCheck = false;

				$item->events = array("click" => $this->item[$i]->even["onclick"]);
				
				$this->options[] = $item;
				if($this->item[$i]->elemento == C_OBJ_MENU){
					if($this->vses["DEBUG"] == "1"){
						$this->deb->dbg(0, $this->item[$i]->nombre,$this->titulo,"menu=".$this->item[$i]->nombre,"m");
					}
					//$k = $i;
					//$this->_index++;
					//$i++;
					
					$i = $this->loadItems($this->item[$i]->nombre, $i+1, $i);
				}
				

			}// end if

			

			$i++;				

		}// wend
		
		return $i;
	}
	
	private function sgMenu($id, $options){
		$orientacion = "";
		switch ($this->orientacion){
			case C_MENU_VERTICAL:
				$orientacion = "vertical";
				break;
			case C_MENU_HORIZONTAL:
				$orientacion = "horizontal";
				break;
			case C_MENU_BIDIMENSIONAL:
				$orientacion = "grid";
				break;
			case C_MENU_SUBMENU:
				$orientacion = "";
				break;
		}// end switch		
		$div = "<div id=\"$id\"></div>";
		
		
		$m = new stdClass;
		
		$m->id = "mm".$id;
		$m->target = $id;
		$m->caption = ($this->sin_titulo == "si")? false: $this->titulo;
		$m->className = array($this->clase, $orientacion);
		$m->type = "default";
		
		$m->pullDeltaX = 10;
		$m->pullDeltaY = 10;
		
		$opt = sg_json_encode($m);
		$this->script = "\nvar men_{$id} = new Sevian.Menu($opt);";
		
		
		foreach($options as $v){
			$opt = sg_json_encode($v);
			//hr($v->id.".....".$v->caption, "red");
			$this->script .= "\nmen_{$id}.add($opt);";
		}
		
		
/*var m = new _menu(
	{
		id: "menu45",
		target: "#menu_01",
		className: "menu",
		type: "default",
		caption:"Menú Secundario",
			
		wCheck: true,
		check:function(value, id, parentId, level){
			//alert(value)
			db(444+"..."+id+"---"+value+" level: "+level);
			
		}	
		
	}
);		
	*/		
		return $div;
	}
	
	
	function menu_normal(){
		$t = new cls_table($this->filas,$this->columnas);
		
		
		$t->width = ($this->width!="0")?$this->width:"";
		if($this->height !="0"){
			$t->height = $this->height;
		}// end if


		$t->border = $this->border;
		$t->cellspacing = $this->cellspacing;
		$t->cellpadding = $this->cellpadding;
		$t->class = $this->clase_menu." sg_menu";
		$t->property .= " data-orientacion=\"$this->orientacion\"";
		//===========================================================
		$this->calcular_ancho();
		//===========================================================
		
		if($this->delta == 1 or $this->delta2 == 1){
			$t->header_row(0);
			if($this->delta2== 1){
				$t->merge_col(0);
			}else{
				$t->merge_row(0);
			}// end if
			$t->cell[0][0]->width = $this->ancho_columna;
			$t->cell[0][0]->text = $this->titulo;
			$t->cell[0][0]->class = $this->clase_titulo;
			if($this->estilo_titulo != ""){
				$t->cell[0][0]->style = $this->estilo_titulo;
			}// end if
			if($prop = extraer_para($this->propiedades_titulo)){
				foreach($prop as $para => $valor){
					eval("\$t->cell[0][0]->$para = \"$valor\";");
				}// next
			}// end if
		}// end if
		$i=0;
		//===========================================================
		for($f=$this->delta;$f<$this->filas;$f++){
			for($c=$this->delta2;$c<$this->columnas;$c++){
				$t->cell[$f][$c]->width = $this->ancho_columna;
				if($i < $this->nro_items){
					$this->vexp["NRO_ITEM"] = $i + 1;
					$this->evaluar_clases($this->item[$i]);
					$t->cell[$f][$c]->class = $this->item[$i]->clase_item;
					$texto = $this->formar_item($this->item[$i]);
					$indicador = "";
					$t->cell[$f][$c]->text = $indicador.$texto;
					//===========================================================
					if($this->item[$i]->deshabilitado != "si"){
						

						if($this->sin_efecto != "si"){
							$t->cell[$f][$c]->onmouseover = "this.className = '".$this->item[$i]->clase_over."';";
							$t->cell[$f][$c]->onmouseout = "this.className = '".$this->item[$i]->clase_item."';";
						}// end if	
						if($prop = extraer_para_sum($this->evaluar_exp($this->item[$i]->eventos))){
							//hr($this->item[$i]->titulo."...".$this->item[$i]->eventos);
							foreach($prop as $para => $valor){
								eval("\$this->item[\$i]->even[\"$para\"] = \"$valor\";");
								
								
								
								eval("\$t->cell[\$f][\$c]->$para .= \"$valor\";");
							}// next
							
						}// end if
						
					}else{
						$t->cell[$f][$c]->class = $this->item[$i]->clase_disabled;
					}// end if
					if($this->item[$i]->estilo != ""){
						$t->cell[$f][$c]->style = $this->evaluar_exp($this->item[$i]->estilo);
					}// end if
					if($prop = extraer_para($this->evaluar_exp($this->item[$i]->propiedades))){
						foreach($prop as $para => $valor){
							eval("\$t->cell[\$f][\$c]->$para = \"$valor\";");
						}// next
					}// end if
					if($this->item[$i]->title!=""){
						$t->cell[$f][$c]->title = $this->item[$i]->title;
					}// end if
					if($this->item[$i]->abrir_menu!=""){
						$t->cell[$f][$c]->onclick = $this->item[$i]->abrir_menu;
					}// end if
					//===========================================================
					$i++;
				}else{
					$t->cell[$f][$c]->class = $this->clase_item;					
					if($this->estilo_item != ""){
						$t->cell[$f][$c]->style = $this->estilo_item;
					}// end if
					if($prop = extraer_para($this->propiedades_item)){
						foreach($prop as $para => $valor){
							eval("\$t->cell[\$f][\$c]->$para = \"$valor\";");
						}// next
					}// end if
				}// end if
			}// next
		}// next
		//===========================================================
		$this->crear_script();

		return $t->control();
	}// end function
	//===========================================================
	function menu_patron(){
		if(!$this->diagrama = $this->leer_diagrama($this->plantilla)){
			return "";
		}// end if
		$this->diagrama = $this->evaluar_var($this->diagrama);
		//===========================================================
		$this->diagrama = str_replace("--class--",$this->clase_menu." sg_menu",$this->diagrama);		
		$this->diagrama = str_replace("--colspan--",$this->columnas,$this->diagrama);
		$this->diagrama = str_replace("--rowspan--",$this->columnas,$this->diagrama);
		$this->diagrama = str_replace("--width--",$this->width,$this->diagrama);
		$this->diagrama = str_replace("--border--",$this->border,$this->diagrama);
		$this->diagrama = str_replace("--cellspacing--",$this->cellspacing,$this->diagrama);
		$this->diagrama = str_replace("--cellpadding--",$this->cellpadding,$this->diagrama);
		//===========================================================
		$this->calcular_ancho();
		$this->diagrama = str_replace("--ancho_columna--",$this->ancho_columna,$this->diagrama);
		//===========================================================
		$this->fila_titulo = extraer_patron($this->diagrama,$this->id_fila_titulo);
		$this->fila_rep = extraer_patron($this->diagrama,$this->id_fila);
		$this->columna_rep = extraer_patron($this->diagrama,$this->id_columna);	
		//===========================================================
		if ($this->sin_titulo=="si"){
			$this->diagrama = formar_diagrama($this->diagrama,$this->id_fila_titulo,"");
		}else{
			if ($this->clase_titulo != ""){
				$clase_titulo = "class:".$this->clase_titulo.";";
			}else{		
				$clase_titulo = "";
			}// end if
			$linea_x = $this->fila_titulo;
			$propiedades_x = $this->evaluar_prop($clase_titulo.$this->propiedades_titulo,$this->estilo_titulo);
			$linea_x = str_replace("--titulo--",$this->titulo,$linea_x);
			$linea_x = str_replace("--propiedades--",$propiedades_x,$linea_x);
			$this->diagrama = formar_diagrama($this->diagrama,$this->id_fila_titulo,$linea_x);
		}// end if
		//===========================================================
		$lineas = "";
		$i=0;
		//===========================================================
		for($f=$this->delta;$f<$this->filas;$f++){
			$celdas = "";
			for($c=0;$c<$this->columnas;$c++){
				if($i < $this->nro_items){
					$this->vexp["NRO_ITEM"] = $i+1;
					$this->evaluar_clases($this->item[$i]);
					$texto = $this->formar_item($this->item[$i]);
					if($this->item[$i]->expresiones != ""){
						$this->vexp = array_merge($this->item[$i]->vexp,$prop);
					}// end if
					$linea_x = $this->evaluar_exp($this->columna_rep);
					$linea_x = str_replace("--item--",$texto,$this->evaluar_exp($this->columna_rep));
					$linea_x = str_replace("--indicador--",$this->item[$i]->indicador,$linea_x);
					//===========================================================
					if($this->item[$i]->deshabilitado != "si"){
						if($this->sin_efecto != "si"){
							$this->item[$i]->evento->onmouseover = "this.className = '".$this->item[$i]->clase_over."';".$this->item[$i]->evento->onmouseover;
							$this->item[$i]->evento->onmouseout = "this.className = '".$this->item[$i]->clase_item."';".$this->item[$i]->evento->onmouseout;
						}// end if
						//===========================================================
						$eventos_x = "";
						if($prop = extraer_para_sum($this->evaluar_exp($this->item[$i]->eventos))){
							foreach($prop as $para => $valor){
								$eventos_x .= " $para=\"$valor\"";
							}// next
						}// end if
						$linea_x = str_replace("--eventos--",$eventos_x,$linea_x);
						//===========================================================
						if ($this->item[$i]->clase_item != ""){
							$clase_item = "class:".$this->item[$i]->clase_item.";";
						}else{		
							$clase_item = "";
						}// end if
						$propiedades_x = $this->evaluar_prop($clase_item.$this->item[$i]->propiedades,$this->item[$i]->estilo);
					}else{
						if ($this->item[$i]->clase_disabled != ""){
							$clase_disabled = "class:".$this->item[$i]->clase_disabled.";";
						}else{		
							$clase_disabled = "";
						}// end if
						$propiedades_x = $this->evaluar_prop($clase_disabled.$this->propiedades_item,$this->estilo_item);
					}// end if
					$linea_x = str_replace("--propiedades--",$propiedades_x,$linea_x);
					//===========================================================
					$celdas .= "\n\t".$linea_x;				
					$i++;
				}else{
					if ($this->clase_item != ""){
						$clase_item = "class:".$this->clase_item.";";
					}else{		
						$clase_item = "";
					}// end if
					$propiedades_x = $this->evaluar_prop($clase_item.$this->propiedades_item,$this->estilo_item);
					$linea_x = str_replace("--propiedades--",$propiedades_x,$this->evaluar_exp($this->columna_rep));
					$celdas .= "\n\t".$linea_x; 
				}// end if
			}// next
			$lineas .= "\n".formar_diagrama($this->fila_rep,$this->id_columna,$celdas);
		}// next
		//===========================================================
		$lineas = str_replace("--indicador--","",$lineas);
		$lineas = str_replace("--item--","&nbsp;",$lineas);
		$lineas = str_replace("--eventos--","",$lineas);
		$lineas = str_replace("--propiedades--","",$lineas);
		return formar_diagrama($this->diagrama,$this->id_fila,$lineas);
	}// end function
	//===========================================================
	function evaluar_prop($propiedades,$estilo){
		$propiedades_x = "";	
		if($estilo != ""){
			$propiedades_x .= "style=\"".reparar_sep($estilo)."\" ";
		}// end if
		if($prop = extraer_para($propiedades)){
			foreach($prop as $para => $val){
				if ($val==""){
					continue;
				}// end if
				$propiedades_x .= "$para=\"$val\" ";
			}// next
		}// end if
		return $propiedades_x;
	}// end function
	//===========================================================
	function evaluar_clases(&$item){
		if($item->clase != ""){
			if($item->clase_item == ""){
				$item->clase_item = $item->clase."_men_itm";
			}//end if
			if($item->clase_over == ""){
				$item->clase_over = $item->clase."_men_itm";
			}//end if
			if($item->clase_disabled == ""){
				$item->clase_disabled = $item->clase."_men_itm_disabled";
			}//end if
			if($item->clase_icono == ""){
				$item->clase_icono = $item->clase."_men_itm_icono";
			}//end if
		}else{
			if($item->clase_item == ""){
				$item->clase_item = $this->clase_item;
			}//end if
			if($item->clase_over == ""){
				$item->clase_over = $this->clase_over;
			}//end if
			if($item->clase_disabled == ""){
				$item->clase_disabled = $this->clase_disabled;
			}//end if
			if($item->clase_icono == ""){
				$item->clase_icono = $this->clase_icono;
			}//end if
		}//end if
	}// end fucntion
	//===========================================================
	function formar_item(&$item){
		//$item->titulo = $item->titulo;
		

		if($item->elemento==C_ELEM_MENU && $item->indicador == "si"){
			$indicador =  "<img src=\"$this->indicador_img\" style='margin-right:6px;float:right'>";
		}else{
			$indicador = "";
		}// end if
		
		
		switch ($this->imagen){
		case C_MENU_IMG_SOLO_TEXTO:
			$texto = $indicador.$item->titulo;
			break;
		case C_MENU_IMG_SOLO_IMAGEN:
			$icono=$this->obj_imagen($item);
			$texto = $icono;
			break;
		case C_MENU_IMG_TXT_DERECHA:
			$item->align = "left";
			$icono=$this->obj_imagen($item);
			$texto = $icono.$indicador.$item->titulo;
			break;
		case C_MENU_IMG_TXT_IZQUIERDA:
			$item->align = "right";
			$icono=$this->obj_imagen($item);
			$texto = $icono.$item->titulo;
			break;
		case C_MENU_IMG_TXT_ARRIBA:
			$icono=$this->obj_imagen($item);
			$texto = $indicador.$item->titulo."<br>".$icono;
			break;
		case C_MENU_IMG_TXT_ABAJO:
			$icono=$this->obj_imagen($item);
			$texto = $icono."<br>".$indicador.$item->titulo;
			break;
		case C_MENU_MEN_TXT_DERECHA:
			$item->align = "absmiddle";
			$icono=$this->obj_imagen($item);
			$texto = $icono."&nbsp;".$item->titulo;
			break;
		}// end switch
		return $texto;
	}// end function	
	//===========================================================
	function obj_imagen(&$item){
		if($item->imagen!=""){
			$img = new cls_element_html("img");
			if($item->imagen_ancho != ""){
				$img->width = $item->imagen_ancho;
			}else{
				$img->width = $this->imagen_ancho;
			}// end if
			if($item->imagen_alto != ""){
				$img->height = $item->imagen_alto;
			}else{
				$img->height = $this->imagen_alto;
			}// end if
			if($item->imagen_align != ""){
				$img->align = $item->imagen_align;
			}else{
				$img->align = $this->imagen_align;
			}// end if
			if($item->clase_icono != ""){
				$img->class = $item->clase_icono;
			}else{
				$img->class = $this->clase_icono;
			}// end if
			if($item->deshabilitado == "si" and $item->imagen_disabled != ""){
				$item->imagen = $item->imagen_disabled;	
			}else if($item->imagen_over != ""){
				$img->onmouseover = "this.src='".$item->imagen_over."'";
				$img->onmouseout = "this.src='".$item->imagen."'";
			}// end if
			$img->src = $item->imagen;
			return $img->control();
		}// end if
		return false;
	}// end function
	//===========================================================
	function calcular_ancho(){
		if($this->ancho_columna == ""){
			if($this->ancho_libre!="si"){
				$medida = medida($this->width);
				$this->ancho_columna = @floor((($medida[2]=="%")?100:$medida[1])/$this->columnas).$medida[2];
			}else{
				$this->ancho_columna = "";
			}// end if
		}// end if
		$this->vexp["ANCHO_COLUMNA"] = $this->ancho_columna;
	}// end fucntion
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
	function crear_script(){
		if ($this->posicion > 0){
			$script = "";
			$script .= "\nvar $this->menu = new cls_menu(capa);\n";
			$script .= "$this->menu.border = 2//\"$this->border\";\n";
			$script .= "$this->menu.cellspacing = \"$this->cellspacing\";\n";
			$script .= "$this->menu.cellpadding = \"$this->cellpadding\";\n";
			$script .= "$this->menu.width = \"150\";\n";
			
			$script .= "$this->menu.clase = \"$this->clase\";\n";
			if($this->clase_menu){
				$script .= "$this->menu.clase_menu = \"$this->clase_menu\";\n";
			}// end if
			if($this->clase_item){
				$script .= "$this->menu.clase_item = \"$this->clase_item\";\n";
			}// end if
			if($this->periodo){
				$script .= "$this->menu.periodo = \"$this->periodo\";\n";
			}// end if
			if($this->indicador_img){
				$script .= "$this->menu.indicador_img = \"$this->indicador_img\";\n";
			}// end if
			if($this->auto_menu){
				$script .= "$this->menu.auto_menu = \"$this->auto_menu\";\n";
			}// end if
			foreach($this->item as $k){
			//hr($k->titulo."....".$k->even["onclick"]);
				if($k->oculto=="si"){
					continue;
				}// end if
	
			
			
				$script .= "$this->menu.crear(\"$k->accion\",\"$k->titulo\",\"".$k->even["onclick"]."\");\n";
				$script .= "$this->menu.items[\"$k->accion\"].indicador = ".(($k->elemento==C_ELEM_MENU && $k->indicador)?"true":"false").";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase = \"$k->clase\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase_item = \"$k->clase_item\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase_over = \"$k->clase_over\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase_disabled = \"$k->clase_disabled\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].estilo = \"$k->estilo\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].sin_evento = \"$k->sin_evento\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].auto_menu = \"$k->auto_menu\";\n";
			}// next
			$this->script = $script;
		}// end if
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


	
//===========================================================
class cls_menu1{
	var $menu = "";
	var $menu_titulo = "";
	var $clase = "";
	var $plantilla = "";
	var $parametros = "";
	var $expresiones = "";
	var $estilo = "";
	var $propiedades = "";
	var $estilo_titulo = "";
	var $propiedades_titulo = "";
	var $estilo_item = "";
	var $propiedades_item = "";
	var $status = "";
	//===========================================================
	var $tipo = 1;//C_MENU_NORMAL;
	var $imagen = C_MENU_IMG_TXT_DERECHA;
	var $panel = "";
	var $orden = "";
	var $orientacion = "";
	var $diagrama = "";
	var $indicador_img = C_MENU_IMG_IND;
	//===========================================================
	var $id = "";
	var $conexion = false;
	var $item = array();
	var $nro_items = 0;
	var $delta = "";
	var $filas = "0";
	var $columnas = "3";
	//===========================================================
	var $clase_menu = "";
	var $clase_titulo = "";
	var $clase_item = "";
	var $clase_over = "";
	var $clase_disabled = "";
	var $clase_icono = "";
	//===========================================================
	var $sin_titulo = "no";
	var $sin_efecto = "no";
	var $ancho_libre = "no";
	//===========================================================
	var $width = "100%";
	var $border = "0";
	var $cellspacing = "2";
	var $cellpadding = "2";
	var $ancho_columna = "";

	var $imagen_alto = "";
	var $imagen_ancho = "";
	var $imagen_align = "";
	//===========================================================
	var	$cfg_acciones = C_CFG_ACCIONES;
	var	$cfg_men_acc = C_CFG_MEN_ACC;
	var	$cfg_menus = C_CFG_MENUS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	var	$cfg_usr_acc = C_CFG_USR_ACC;
	var	$cfg_gpo_acc = C_CFG_GPO_ACC;
	var $cfg_gpo_usr = C_CFG_GPO_USR;
	var $cfg_gpo_men_acc = C_CFG_GPO_MEN_ACC;
	//===========================================================
	var $id_fila = "fila_rep";
	var $id_columna = "columna_rep";
	var $id_fila_titulo = "fila_titulo";
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	//===========================================================
	function cls_menu($menu_x="",$id_x=""){
		if($menu_x!=""){
			$this->menu = $menu_x;
		}// end if
		if($id_x!=""){
			$this->id = $id_x;
		}// end if
	}// end function
	//===========================================================
	function control($menu_x="",$usuario_x=""){
		if($menu_x!=""){
			$this->menu = $menu_x;
		}// end if
		if($usuario_x!=""){
			$this->usuario = $usuario_x;
		}// end if
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		//===========================================================
		$cn = &$this->conexion;
		$cn->query = "	SELECT 
						a.accion, a.titulo, a.panel, a.elemento, a.nombre, a.modo, a.registro, a.pagina, a.referencia, a.target, 
						a.proc_ini, a.proc_fin, a.cmd_ini, a.cmd_fin, a.interaccion, a.estructura, a.parametros,
						a.obj, a.obj_funcion, a.secuencia, a.nodo, a.funcion, a.sesion, 
						a.eventos, a.validar, a.confirmar, a.mensaje, 
						a.tipo, a.clase, a.estilo, a.propiedades, a.status, 						
						
						b.menu, b.accion, b.clase as clase_b, b.orden, 
						b.cmd_ini as cmd_ini_b, b.cmd_fin as cmd_fin_b, b.proc_ini as proc_ini_b, b.proc_fin as proc_fin_b,
						b.parametros as parametros_b, b.expresiones, b.estilo as estilo_b, b.propiedades as propiedades_b,

						c.titulo as menu_titulo, c.clase as menu_clase, c.plantilla as menu_plantilla,c.parametros as menu_parametros,
						c.expresiones as menu_expresiones, c.estilo as menu_estilo,c.propiedades as menu_propiedades,
						c.estilo_titulo, c.propiedades_titulo, c.estilo_item, c.propiedades_item

						FROM $this->cfg_men_acc as b
						INNER JOIN $this->cfg_menus as c ON c.menu = b.menu
						INNER JOIN $this->cfg_acciones as a ON b.accion = a.accion
						INNER JOIN $this->cfg_gpo_usr as g ON 1=1
						LEFT JOIN $this->cfg_gpo_acc as f ON f.accion = b.accion AND g.grupo = f.grupo
						LEFT JOIN $this->cfg_gpo_men_acc as h ON h.menu = b.menu AND h.accion = b.accion AND h.grupo = g.grupo
						LEFT JOIN $this->cfg_usr_acc as e ON e.accion = b.accion AND e.usuario = g.usuario
						WHERE b.menu = '$this->menu' AND g.usuario = '$this->usuario'
						AND (e.permitir=1 or e.accion IS NULL)
						AND (f.permitir=1 or f.accion IS NULL)
						AND (h.permitir=1 or h.accion IS NULL)
						GROUP BY b.accion
						ORDER BY b.orden";	
						//hr($cn->query);
		$result = $cn->ejecutar($cn->query);

		//===========================================================				
		$i=0;
		if($rs = $cn->consultar($result)){
			$this->vpara = &$rs;
			$this->nombre = $rs["menu"];
			$this->titulo = $rs["menu_titulo"];
			if($rs["menu_clase"]!=""){
				$this->clase = $rs["menu_clase"];
			}// end if
			$this->plantilla =  $rs["menu_plantilla"];
			$this->parametros .= reparar_sep($rs["menu_parametros"]);
			$this->expresiones = reparar_sep($rs["menu_expresiones"]);
			$this->estilo .= reparar_sep($rs["menu_estilo"]);
			$this->propiedades .= reparar_sep($rs["menu_propiedades"]);
			$this->estilo_titulo .= reparar_sep($rs["estilo_titulo"]);
			$this->propiedades_titulo .= reparar_sep($rs["propiedades_titulo"]);
			$this->estilo_item .= reparar_sep($rs["estilo_item"]);
			$this->propiedades_item .= reparar_sep($rs["propiedades_item"]);
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
				$prop["titulo"] = $rs["titulo"];
				$this->vpara = array_merge($this->vpara,$prop);
			}// end if
			//===========================================================
			$this->expresiones = reparar_sep($this->evaluar_todo($this->expresiones));
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = array_merge($this->vexp, $prop);
			}// end if
			//===========================================================
			$this->estilo = $this->evaluar_todo($this->estilo);
			$this->propiedades = $this->evaluar_todo($this->propiedades);
			$this->estilo_titulo = $this->estilo.$this->evaluar_todo($this->estilo_titulo);
			$this->propiedades_titulo = $this->propiedades.$this->evaluar_todo($this->propiedades_titulo);
			$this->estilo_item = $this->estilo.$this->evaluar_todo($this->estilo_item);
			$this->propiedades_item = $this->propiedades.$this->evaluar_todo($this->propiedades_item);
			//===========================================================
			
			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg($this->panel,$this->nombre,$this->titulo,"menu=$this->nombre","m");
			}// end if
			
			
			
			do{
				$rs["parametros"] = reparar_sep($rs["parametros_b"]).reparar_sep($rs["parametros"]);
				$rs["estilo"] = $this->estilo_item.reparar_sep($rs["estilo_b"]).reparar_sep($rs["estilo"]);
				$rs["propiedades"] = $this->propiedades_item.reparar_sep($rs["propiedades_b"]).reparar_sep($rs["propiedades"]);
				if($rs["clase"]==""){
					$rs["clase"] = $rs["clase_b"];
				}// end if
				if($rs["clase"]==""){
					$rs["clase"] = $this->clase;
				}// end if
				if($rs["proc_ini"] == ""){
					$rs["proc_ini"] = $rs["proc_ini_b"];
				}// end if
				if($rs["proc_fin"] == ""){
					$rs["proc_fin"] = $rs["proc_fin_b"];
				}// end if
				if($rs["cmd_ini"] == ""){
					$rs["cmd_ini"] = $rs["cmd_ini_b"];
				}// end if
				if($rs["cmd_fin"] == ""){
					$rs["cmd_fin"] = $rs["cmd_fin_b"];
				}// end if
				//===========================================================
				$this->item[$i] = new cfg_item();
				$this->item[$i]->vses = &$this->vses;
				$this->item[$i]->vform = &$this->vform;
				$this->item[$i]->vexp = &$this->vexp;
				$this->item[$i]->deb = &$this->deb;
				$this->item[$i]->menu_orientacion = $this->orientacion;
				$this->item[$i]->smenu_posicion = $this->posicion;
				$this->item[$i]->panel_default = $this->panel_default;
				$this->item[$i]->panel_actual = $this->panel;
				
				
				$this->item[$i]->ejecutar($rs);


				if($this->item[$i]->menuest == "si"){
					$r = $this->vses["menu_nivel"];
					$nro_menuest = count($r);
					$j = 0;
					foreach($r as $k => $v){
						$j++;
						$rss = $rs;
						$rss["titulo"] = "&raquo; ".$this->vses["menut_nivel"][$k];
						$rss["estructura"]=$v;	
						$this->item[$i] = new cfg_item();
						$this->item[$i]->vses = &$this->vses;
						$this->item[$i]->vform = &$this->vform;
						$this->item[$i]->vexp = &$this->vexp;
						$this->item[$i]->deb = &$this->deb;
						$this->item[$i]->menu_orientacion = $this->orientacion;
						$this->item[$i]->smenu_posicion = $this->posicion;
						$this->item[$i]->panel_default = $this->panel_default;
						$this->item[$i]->panel_actual = $this->panel;
						$this->item[$i]->restablecer_est = "si";

						if($nro_menuest == $j){
							$this->item[$i]->deshabilitado = true;
						}// end if

						
						$this->item[$i]->ejecutar($rss);
						$i++;
					}// next
					
					$i--;
					
					
					
				}// end if

				if($this->item[$i]->oculto=="si"){
					continue;
				}// end if
				
				$i++;
			}while ($rs = $cn->consultar($result));
			$this->nro_items = $i++;
		}else{
			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg($this->panel,$this->menu,$this->menu,"menu=$this->menu","m");
			}// end if
		}// end if
		//===========================================================
		if($this->clase != ""){
			if($this->clase_menu == ""){
				$this->clase_menu = $this->clase."_men";		
			}//end if
			if($this->clase_titulo == ""){
				$this->clase_titulo = $this->clase."_men_titulo";		
			}//end if
			if($this->clase_item == ""){
				$this->clase_item = $this->clase."_men_itm";
			}//end if
			if($this->clase_over == ""){
				$this->clase_over = $this->clase."_men_itm";
			}//end if
			if($this->clase_disabled == ""){
				$this->clase_disabled = $this->clase."_men_itm_disabled";
			}//end if
			if($this->clase_icono == ""){
				$this->clase_icono = $this->clase."_men_itm_icono";
			}//end if
		}// end if
		//===========================================================
		if ($this->sin_titulo == "si"){
			$this->delta = 0;
			$this->delta2 = 0;
		}else{
			if($this->titulo_lateral=="si"){
				$this->delta = 0;
				$this->delta2 = 1;
			}else{
				$this->delta = 1;
				$this->delta2 = 0;
			}// end if			
		}// end if
		//===========================================================
		switch ($this->orientacion){
		case C_MENU_VERTICAL:
			$this->columnas = 1;
			$this->filas = $this->nro_items;
			break;
		case C_MENU_HORIZONTAL:
			$this->filas = 1;
			$this->columnas = $this->nro_items;
			break;
		case C_MENU_BIDIMENSIONAL:
			if($this->filas == "0" and $this->columnas != "0"){
				if($this->columnas > $this->nro_items){
					$this->columnas = $this->nro_items;
				}// end if
				$this->filas = ceil($this->nro_items/$this->columnas);	
			}else if ($this->filas != "0" and $this->columnas == "0"){
				$this->columnas = ceil($this->nro_items/$this->filas);
				if(ceil($this->nro_items/ $this->columnas)<$this->filas){
					$this->filas = $this->nro_items/ $this->columnas;
				}// end if
			}// end if
			break;
		case C_MENU_SUBMENU:
			$this->crear_script();
			return true;
			break;
		}// end switch
		$this->filas += $this->delta;
		$this->columnas += $this->delta2;
		switch ($this->tipo){
		case C_MENU_PATRON:
			return $this->menu_patron();
			break;
		case C_MENU_DISENO:
			return $this->menu_patron();
			break;
		case C_MENU_ARCHIVO:
			return $this->menu_patron();
			break;
		case C_MENU_NORMAL:
		default:
			//$this->crear_script();
			return $this->menu_normal();
			
			break;

	
		
		}// end switch
		
		//;
		
	}// end function
	//===========================================================
	function menu_normal(){
		$t = new cls_table($this->filas,$this->columnas);
		
		
		$t->width = ($this->width!="0")?$this->width:"";
		if($this->height !="0"){
			$t->height = $this->height;
		}// end if


		$t->border = $this->border;
		$t->cellspacing = $this->cellspacing;
		$t->cellpadding = $this->cellpadding;
		$t->class = $this->clase_menu." sg_menu";
		$t->property .= " data-orientacion=\"$this->orientacion\"";
		//===========================================================
		$this->calcular_ancho();
		//===========================================================
		
		if($this->delta == 1 or $this->delta2 == 1){
			$t->header_row(0);
			if($this->delta2== 1){
				$t->merge_col(0);
			}else{
				$t->merge_row(0);
			}// end if
			$t->cell[0][0]->width = $this->ancho_columna;
			$t->cell[0][0]->text = $this->titulo;
			$t->cell[0][0]->class = $this->clase_titulo;
			if($this->estilo_titulo != ""){
				$t->cell[0][0]->style = $this->estilo_titulo;
			}// end if
			if($prop = extraer_para($this->propiedades_titulo)){
				foreach($prop as $para => $valor){
					eval("\$t->cell[0][0]->$para = \"$valor\";");
				}// next
			}// end if
		}// end if
		$i=0;
		//===========================================================
		for($f=$this->delta;$f<$this->filas;$f++){
			for($c=$this->delta2;$c<$this->columnas;$c++){
				$t->cell[$f][$c]->width = $this->ancho_columna;
				if($i < $this->nro_items){
					$this->vexp["NRO_ITEM"] = $i + 1;
					$this->evaluar_clases($this->item[$i]);
					$t->cell[$f][$c]->class = $this->item[$i]->clase_item;
					$texto = $this->formar_item($this->item[$i]);
					$indicador = "";
					$t->cell[$f][$c]->text = $indicador.$texto;
					//===========================================================
					if($this->item[$i]->deshabilitado != "si"){
						

						if($this->sin_efecto != "si"){
							$t->cell[$f][$c]->onmouseover = "this.className = '".$this->item[$i]->clase_over."';";
							$t->cell[$f][$c]->onmouseout = "this.className = '".$this->item[$i]->clase_item."';";
						}// end if	
						if($prop = extraer_para_sum($this->evaluar_exp($this->item[$i]->eventos))){
							//hr($this->item[$i]->titulo."...".$this->item[$i]->eventos);
							foreach($prop as $para => $valor){
								eval("\$this->item[\$i]->even[\"$para\"] = \"$valor\";");
								
								
								
								eval("\$t->cell[\$f][\$c]->$para .= \"$valor\";");
							}// next
							
						}// end if
						
					}else{
						$t->cell[$f][$c]->class = $this->item[$i]->clase_disabled;
					}// end if
					if($this->item[$i]->estilo != ""){
						$t->cell[$f][$c]->style = $this->evaluar_exp($this->item[$i]->estilo);
					}// end if
					if($prop = extraer_para($this->evaluar_exp($this->item[$i]->propiedades))){
						foreach($prop as $para => $valor){
							eval("\$t->cell[\$f][\$c]->$para = \"$valor\";");
						}// next
					}// end if
					if($this->item[$i]->title!=""){
						$t->cell[$f][$c]->title = $this->item[$i]->title;
					}// end if
					if($this->item[$i]->abrir_menu!=""){
						$t->cell[$f][$c]->onclick = $this->item[$i]->abrir_menu;
					}// end if
					//===========================================================
					$i++;
				}else{
					$t->cell[$f][$c]->class = $this->clase_item;					
					if($this->estilo_item != ""){
						$t->cell[$f][$c]->style = $this->estilo_item;
					}// end if
					if($prop = extraer_para($this->propiedades_item)){
						foreach($prop as $para => $valor){
							eval("\$t->cell[\$f][\$c]->$para = \"$valor\";");
						}// next
					}// end if
				}// end if
			}// next
		}// next
		//===========================================================
		$this->crear_script();

		return $t->control();
	}// end function
	//===========================================================
	function menu_patron(){
		if(!$this->diagrama = $this->leer_diagrama($this->plantilla)){
			return "";
		}// end if
		$this->diagrama = $this->evaluar_var($this->diagrama);
		//===========================================================
		$this->diagrama = str_replace("--class--",$this->clase_menu." sg_menu",$this->diagrama);		
		$this->diagrama = str_replace("--colspan--",$this->columnas,$this->diagrama);
		$this->diagrama = str_replace("--rowspan--",$this->columnas,$this->diagrama);
		$this->diagrama = str_replace("--width--",$this->width,$this->diagrama);
		$this->diagrama = str_replace("--border--",$this->border,$this->diagrama);
		$this->diagrama = str_replace("--cellspacing--",$this->cellspacing,$this->diagrama);
		$this->diagrama = str_replace("--cellpadding--",$this->cellpadding,$this->diagrama);
		//===========================================================
		$this->calcular_ancho();
		$this->diagrama = str_replace("--ancho_columna--",$this->ancho_columna,$this->diagrama);
		//===========================================================
		$this->fila_titulo = extraer_patron($this->diagrama,$this->id_fila_titulo);
		$this->fila_rep = extraer_patron($this->diagrama,$this->id_fila);
		$this->columna_rep = extraer_patron($this->diagrama,$this->id_columna);	
		//===========================================================
		if ($this->sin_titulo=="si"){
			$this->diagrama = formar_diagrama($this->diagrama,$this->id_fila_titulo,"");
		}else{
			if ($this->clase_titulo != ""){
				$clase_titulo = "class:".$this->clase_titulo.";";
			}else{		
				$clase_titulo = "";
			}// end if
			$linea_x = $this->fila_titulo;
			$propiedades_x = $this->evaluar_prop($clase_titulo.$this->propiedades_titulo,$this->estilo_titulo);
			$linea_x = str_replace("--titulo--",$this->titulo,$linea_x);
			$linea_x = str_replace("--propiedades--",$propiedades_x,$linea_x);
			$this->diagrama = formar_diagrama($this->diagrama,$this->id_fila_titulo,$linea_x);
		}// end if
		//===========================================================
		$lineas = "";
		$i=0;
		//===========================================================
		for($f=$this->delta;$f<$this->filas;$f++){
			$celdas = "";
			for($c=0;$c<$this->columnas;$c++){
				if($i < $this->nro_items){
					$this->vexp["NRO_ITEM"] = $i+1;
					$this->evaluar_clases($this->item[$i]);
					$texto = $this->formar_item($this->item[$i]);
					if($this->item[$i]->expresiones != ""){
						$this->vexp = array_merge($this->item[$i]->vexp,$prop);
					}// end if
					$linea_x = $this->evaluar_exp($this->columna_rep);
					$linea_x = str_replace("--item--",$texto,$this->evaluar_exp($this->columna_rep));
					$linea_x = str_replace("--indicador--",$this->item[$i]->indicador,$linea_x);
					//===========================================================
					if($this->item[$i]->deshabilitado != "si"){
						if($this->sin_efecto != "si"){
							$this->item[$i]->evento->onmouseover = "this.className = '".$this->item[$i]->clase_over."';".$this->item[$i]->evento->onmouseover;
							$this->item[$i]->evento->onmouseout = "this.className = '".$this->item[$i]->clase_item."';".$this->item[$i]->evento->onmouseout;
						}// end if
						//===========================================================
						$eventos_x = "";
						if($prop = extraer_para_sum($this->evaluar_exp($this->item[$i]->eventos))){
							foreach($prop as $para => $valor){
								$eventos_x .= " $para=\"$valor\"";
							}// next
						}// end if
						$linea_x = str_replace("--eventos--",$eventos_x,$linea_x);
						//===========================================================
						if ($this->item[$i]->clase_item != ""){
							$clase_item = "class:".$this->item[$i]->clase_item.";";
						}else{		
							$clase_item = "";
						}// end if
						$propiedades_x = $this->evaluar_prop($clase_item.$this->item[$i]->propiedades,$this->item[$i]->estilo);
					}else{
						if ($this->item[$i]->clase_disabled != ""){
							$clase_disabled = "class:".$this->item[$i]->clase_disabled.";";
						}else{		
							$clase_disabled = "";
						}// end if
						$propiedades_x = $this->evaluar_prop($clase_disabled.$this->propiedades_item,$this->estilo_item);
					}// end if
					$linea_x = str_replace("--propiedades--",$propiedades_x,$linea_x);
					//===========================================================
					$celdas .= "\n\t".$linea_x;				
					$i++;
				}else{
					if ($this->clase_item != ""){
						$clase_item = "class:".$this->clase_item.";";
					}else{		
						$clase_item = "";
					}// end if
					$propiedades_x = $this->evaluar_prop($clase_item.$this->propiedades_item,$this->estilo_item);
					$linea_x = str_replace("--propiedades--",$propiedades_x,$this->evaluar_exp($this->columna_rep));
					$celdas .= "\n\t".$linea_x; 
				}// end if
			}// next
			$lineas .= "\n".formar_diagrama($this->fila_rep,$this->id_columna,$celdas);
		}// next
		//===========================================================
		$lineas = str_replace("--indicador--","",$lineas);
		$lineas = str_replace("--item--","&nbsp;",$lineas);
		$lineas = str_replace("--eventos--","",$lineas);
		$lineas = str_replace("--propiedades--","",$lineas);
		return formar_diagrama($this->diagrama,$this->id_fila,$lineas);
	}// end function
	//===========================================================
	function evaluar_prop($propiedades,$estilo){
		$propiedades_x = "";	
		if($estilo != ""){
			$propiedades_x .= "style=\"".reparar_sep($estilo)."\" ";
		}// end if
		if($prop = extraer_para($propiedades)){
			foreach($prop as $para => $val){
				if ($val==""){
					continue;
				}// end if
				$propiedades_x .= "$para=\"$val\" ";
			}// next
		}// end if
		return $propiedades_x;
	}// end function
	//===========================================================
	function evaluar_clases(&$item){
		if($item->clase != ""){
			if($item->clase_item == ""){
				$item->clase_item = $item->clase."_men_itm";
			}//end if
			if($item->clase_over == ""){
				$item->clase_over = $item->clase."_men_itm";
			}//end if
			if($item->clase_disabled == ""){
				$item->clase_disabled = $item->clase."_men_itm_disabled";
			}//end if
			if($item->clase_icono == ""){
				$item->clase_icono = $item->clase."_men_itm_icono";
			}//end if
		}else{
			if($item->clase_item == ""){
				$item->clase_item = $this->clase_item;
			}//end if
			if($item->clase_over == ""){
				$item->clase_over = $this->clase_over;
			}//end if
			if($item->clase_disabled == ""){
				$item->clase_disabled = $this->clase_disabled;
			}//end if
			if($item->clase_icono == ""){
				$item->clase_icono = $this->clase_icono;
			}//end if
		}//end if
	}// end fucntion
	//===========================================================
	function formar_item(&$item){
		//$item->titulo = $item->titulo;
		

		if($item->elemento==C_ELEM_MENU && $item->indicador == "si"){
			$indicador =  "<img src=\"$this->indicador_img\" style='margin-right:6px;float:right'>";
		}else{
			$indicador = "";
		}// end if
		
		
		switch ($this->imagen){
		case C_MENU_IMG_SOLO_TEXTO:
			$texto = $indicador.$item->titulo;
			break;
		case C_MENU_IMG_SOLO_IMAGEN:
			$icono=$this->obj_imagen($item);
			$texto = $icono;
			break;
		case C_MENU_IMG_TXT_DERECHA:
			$item->align = "left";
			$icono=$this->obj_imagen($item);
			$texto = $icono.$indicador.$item->titulo;
			break;
		case C_MENU_IMG_TXT_IZQUIERDA:
			$item->align = "right";
			$icono=$this->obj_imagen($item);
			$texto = $icono.$item->titulo;
			break;
		case C_MENU_IMG_TXT_ARRIBA:
			$icono=$this->obj_imagen($item);
			$texto = $indicador.$item->titulo."<br>".$icono;
			break;
		case C_MENU_IMG_TXT_ABAJO:
			$icono=$this->obj_imagen($item);
			$texto = $icono."<br>".$indicador.$item->titulo;
			break;
		case C_MENU_MEN_TXT_DERECHA:
			$item->align = "absmiddle";
			$icono=$this->obj_imagen($item);
			$texto = $icono."&nbsp;".$item->titulo;
			break;
		}// end switch
		return $texto;
	}// end function	
	//===========================================================
	function obj_imagen(&$item){
		if($item->imagen!=""){
			$img = new cls_element_html("img");
			if($item->imagen_ancho != ""){
				$img->width = $item->imagen_ancho;
			}else{
				$img->width = $this->imagen_ancho;
			}// end if
			if($item->imagen_alto != ""){
				$img->height = $item->imagen_alto;
			}else{
				$img->height = $this->imagen_alto;
			}// end if
			if($item->imagen_align != ""){
				$img->align = $item->imagen_align;
			}else{
				$img->align = $this->imagen_align;
			}// end if
			if($item->clase_icono != ""){
				$img->class = $item->clase_icono;
			}else{
				$img->class = $this->clase_icono;
			}// end if
			if($item->deshabilitado == "si" and $item->imagen_disabled != ""){
				$item->imagen = $item->imagen_disabled;	
			}else if($item->imagen_over != ""){
				$img->onmouseover = "this.src='".$item->imagen_over."'";
				$img->onmouseout = "this.src='".$item->imagen."'";
			}// end if
			$img->src = $item->imagen;
			return $img->control();
		}// end if
		return false;
	}// end function
	//===========================================================
	function calcular_ancho(){
		if($this->ancho_columna == ""){
			if($this->ancho_libre!="si"){
				$medida = medida($this->width);
				$this->ancho_columna = @floor((($medida[2]=="%")?100:$medida[1])/$this->columnas).$medida[2];
			}else{
				$this->ancho_columna = "";
			}// end if
		}// end if
		$this->vexp["ANCHO_COLUMNA"] = $this->ancho_columna;
	}// end fucntion
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
	function crear_script(){
		if ($this->posicion > 0){
			$script = "";
			$script .= "\nvar $this->menu = new cls_menu(capa);\n";
			$script .= "$this->menu.border = 2//\"$this->border\";\n";
			$script .= "$this->menu.cellspacing = \"$this->cellspacing\";\n";
			$script .= "$this->menu.cellpadding = \"$this->cellpadding\";\n";
			$script .= "$this->menu.width = \"150\";\n";
			
			$script .= "$this->menu.clase = \"$this->clase\";\n";
			if($this->clase_menu){
				$script .= "$this->menu.clase_menu = \"$this->clase_menu\";\n";
			}// end if
			if($this->clase_item){
				$script .= "$this->menu.clase_item = \"$this->clase_item\";\n";
			}// end if
			if($this->periodo){
				$script .= "$this->menu.periodo = \"$this->periodo\";\n";
			}// end if
			if($this->indicador_img){
				$script .= "$this->menu.indicador_img = \"$this->indicador_img\";\n";
			}// end if
			if($this->auto_menu){
				$script .= "$this->menu.auto_menu = \"$this->auto_menu\";\n";
			}// end if
			foreach($this->item as $k){
			//hr($k->titulo."....".$k->even["onclick"]);
				if($k->oculto=="si"){
					continue;
				}// end if
	
			
			
				$script .= "$this->menu.crear(\"$k->accion\",\"$k->titulo\",\"".$k->even["onclick"]."\");\n";
				$script .= "$this->menu.items[\"$k->accion\"].indicador = ".(($k->elemento==C_ELEM_MENU && $k->indicador)?"true":"false").";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase = \"$k->clase\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase_item = \"$k->clase_item\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase_over = \"$k->clase_over\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].clase_disabled = \"$k->clase_disabled\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].estilo = \"$k->estilo\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].sin_evento = \"$k->sin_evento\";\n";
				$script .= "$this->menu.items[\"$k->accion\"].auto_menu = \"$k->auto_menu\";\n";
			}// next
			$this->script = $script;
		}// end if
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