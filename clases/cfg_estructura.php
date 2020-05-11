<?php
/*****************************************************************
creado: 27/03/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/

//===========================================================
class cfg_estructura{
	
	public $panel = false;
	var $estructura = "";
	var $titulo = "";
	var $plantilla = "";
	var $tipo_plantilla = C_PLANTILLA_DEF;
	var $clase = C_CLASE_DEFAULT;
	var $panel_principal = "";
	var $parametros = "";
	var $expresiones = "";
	var $eventos = "";
	var $status = "0";
	//===========================================================
	var $usuario = "";
	var $diagrama = "";
	//===========================================================
	var $archivos_css = "";
	var $archivos_js = "";
	var $archivos_sg = "";
	var $no_cache = true;
	var $tipo = 1;
	var $imagen = 1;
	var $orientacion = "1";
	//===========================================================
	var $menu;	
	//===========================================================
	var	$cfg_usr_men = C_CFG_USR_MEN;
	var	$cfg_usr_nav = C_CFG_USR_NAV;
	var	$cfg_est_men = C_CFG_EST_MEN;
	var	$cfg_est_ele = C_CFG_EST_ELE;
	var $cfg_estructuras = C_CFG_ESTRUCTURAS;
	var	$cfg_gpo_usr = C_CFG_GPO_USR;
	var	$cfg_gpo_est = C_CFG_GPO_EST;
	var	$cfg_menus = C_CFG_MENUS;
	var	$cfg_usuarios = C_CFG_USUARIOS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	
	public $conexion = false;
	public $script = "";
	public $titulo_item = "";
	//===========================================================
	function __construct($estructura_x="",$usuario_x=""){
		if($estructura_x!=""){
			$this->estructura = $estructura_x;
		}// end if
		if($usuario_x!=""){
			$this->usuario = $usuario_x;
		}// end if
		
		$this->conexion = sgConnection();
	}// end function
	//===========================================================
	function ejecutar($estructura_x="",$usuario_x=""){
		if($estructura_x!=""){
			$this->estructura = $estructura_x;
		}// end if
		if($usuario_x!=""){
			$this->usuario = $usuario_x;
		}// end if
		//===========================================================
		/*
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		*/
		$cn = &$this->conexion;
		$cn->query = "	SELECT 
						c.estructura, c.titulo, c.plantilla, c.clase, c.panel_principal, 
						c.flujo, c.parametros, c.expresiones, c.eventos, c.status, 
						f.usuario
						FROM $this->cfg_estructuras as c 
						INNER JOIN $this->cfg_gpo_est as d ON d.estructura = c.estructura
						INNER JOIN $this->cfg_gpo_usr as e ON e.grupo = d.grupo
						INNER JOIN $this->cfg_usuarios as f ON f.usuario = e.usuario
						WHERE c.estructura = '$this->estructura' AND f.usuario ='$this->usuario'
						AND c.status='1'
			";
			
		$result = $cn->ejecutar();
			
		if($rs = $cn->consultar($result)){
			$this->vpara = &$rs;
			$this->titulo = $rs["titulo"];
			if($rs["plantilla"]!=""){
				$this->plantilla = $rs["plantilla"];
			}// end if
			if($rs["clase"]!=""){
				$this->clase = $rs["clase"];
			}// end if
			$this->panel_principal = $rs["panel_principal"]; 
			$this->flujo = $rs["flujo"]; 
			$this->parametros = $rs["parametros"];
			$this->expresiones = $rs["expresiones"];
			$this->eventos = $rs["eventos"];
			$this->usuario = $rs["usuario"];
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara, $prop);
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
			$this->eventos = $this->evaluar_todo($this->eventos);
			$this->diagrama = $this->evaluar_var($this->leer_diagrama($this->plantilla));
			$this->diagrama = preg_replace("/(?:--([0-9]+)--)/","~~$1~~",$this->diagrama);





			if($this->cambio_est){
	
				$menu = &$this->vses["menu_nivel"];
				
				
				if (is_array($menu)){
					if(in_array($this->estructura, $menu)){
						foreach($menu as $k => $v){
							if($v == $this->estructura){
								$nivel = $k;
								$menu = array_slice($menu,0,$nivel,true);
								break;
							}// end if
						}// next
					}else{
						$nivel = count($menu)+1;
					}// end if
				}else{
					$menu = array();
					$nivel = 1;
				
				}// end if
				$menu[$nivel]=$this->estructura;
				$this->vses["menut_nivel"][$nivel]=($this->titulo_item!="")?$this->titulo_item:$this->titulo;

			}// end if



			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg($this->panel,$this->estructura,$this->titulo." ($this->plantilla)","estructura=$this->estructura","e");
			}// end if



			
			
			return $this->crear_menu();
		}else{
			hr($cn->query);
		}// end if
		return false;
	}// end function
	//===========================================================
	function crear_menu(){
		$cn = &$this->conexion;
		$cn->query = "	SELECT 
						a.estructura, a.menu, a.clase, a.tipo, a.imagen, a.orientacion, a.posicion , a.panel, 
						a.orden, a.parametros, a.expresiones, a.estilo, a.propiedades, 
						a.estilo_titulo, a.propiedades_titulo, a.estilo_item, a.propiedades_item 

						FROM $this->cfg_est_men as a
						LEFT JOIN $this->cfg_usr_men as g ON g.menu = a.menu AND g.usuario = '$this->usuario'
						WHERE a.estructura = '$this->estructura' 
						AND (g.permitir=1 or g.permitir IS NULL) AND a.panel !=0
						ORDER BY a.panel,a.orden
			";
		
		$result = $cn->ejecutar();
		$this->vexp["NRO_MENU"] = 0;
		while($rs = $cn->consultar($result)){
			$mnu = new cls_menu;
			$mnu->panel_default = $this->panel_default;
			
			$mnu->vpara = &$rs;
			$mnu->vses = &$this->vses;
			$mnu->vform = &$this->vform;
			$mnu->vexp = &$this->vexp;
			$mnu->deb = &$this->deb;

			$mnu->usuario = $this->usuario;
			
			$mnu->estructura = $rs["estructura"];
			$mnu->menu = $rs["menu"];
			if($rs["clase"] != ""){
				$mnu->clase = $rs["clase"];
			}else{
				$mnu->clase = $this->clase;
			}// end if
			if($rs["tipo"]){
				$mnu->tipo = $rs["tipo"];
			}else{
				$mnu->tipo = $this->tipo;
			}// end if
			if($rs["imagen"]){
				$mnu->imagen = $rs["imagen"];
			}else{
				$mnu->imagen = $this->imagen;
			}// end if
			if($rs["orientacion"]){
				$mnu->orientacion = $rs["orientacion"];
			}else{
				$mnu->orientacion = $this->orientacion;
			}// end if
			$mnu->posicion = $mnu->orientacion;
			
			$mnu->panel = $rs["panel"];
			$mnu->orden = $rs["orden"];
			$mnu->parametros = $rs["parametros"];
			$mnu->expresiones = $rs["expresiones"];
			$mnu->estilo = $rs["estilo"];
			$mnu->propiedades = $rs["propiedades"];
			$mnu->estilo_titulo = $rs["estilo_titulo"];
			$mnu->propiedades_titulo = $rs["propiedades_titulo"];
			$mnu->estilo_item = $rs["estilo_item"];
			$mnu->propiedades_item = $rs["propiedades_item"];
			//===========================================================
			$this->vpara = &$rs;
			$this->vexp["NRO_MENU"]++;
			$mnu->parametros = $this->evaluar_todo($mnu->parametros);
			if($prop = extraer_para($mnu->parametros)){
				$mnu->vpara = array_merge($mnu->vpara, $prop);
				foreach($prop as $para => $valor){
					eval("\$mnu->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			$mnu->vexp = $this->vexp;
			$mnu->expresiones = $this->evaluar_todo($mnu->expresiones);			
			if($prop = extraer_para($mnu->expresiones)){
				$mnu->vexp = array_merge($mnu->vexp, $prop);
			}// end if
			//===========================================================
			$mnu->estilo = reparar_sep($this->evaluar_todo($mnu->estilo));
			$mnu->propiedades = reparar_sep($this->evaluar_todo($mnu->propiedades));
			$mnu->estilo_titulo = reparar_sep($this->evaluar_todo($mnu->estilo_titulo));
			$mnu->propiedades_titulo = reparar_sep($this->evaluar_todo($mnu->propiedades_titulo));
			$mnu->estilo_item = reparar_sep($this->evaluar_todo($mnu->estilo_item));
			$mnu->propiedades_item = reparar_sep($this->evaluar_todo($mnu->propiedades_item));
			//===========================================================
			$mnu->usuario = $this->usuario;
			if(isset($this->menu[$mnu->panel])){
				$this->menu[$mnu->panel] .= $mnu->control();
			}else{
				$this->menu[$mnu->panel] = $mnu->control();
			}
			
			$this->script .= $mnu->script;
		}// end while
		return $this->menu;
	}// end funtion
	//===========================================================
	function crear_elem(){
		$cn = &$this->conexion;
		$cn->query = "	SELECT 	estructura, panel, elemento, nombre, modo, 
								registro, pagina, para_obj, dinamico, clase, parametros  
						FROM $this->cfg_est_ele
						WHERE estructura = '$this->estructura'";

		$result = $cn->ejecutar();
		
		while($rs = $cn->consultar($result)){

			$panel = $rs["panel"];
			if(!isset($this->elem[$panel])){
				$this->elem[$panel] = new cls_panel;
				$this->elem[$panel]->metodo = C_METODO;
			}// end if
			
			if($this->elem[$panel]->actualizado and !$this->ini_est){
				continue;
			}// end if
			$this->elem[$panel]->actualizado = true;
			$this->elem[$panel]->panel = $rs["panel"];
			$this->elem[$panel]->estructura = $rs["estructura"];
			$this->elem[$panel]->elemento = $rs["elemento"];
			$this->elem[$panel]->nombre = $rs["nombre"];
			$this->elem[$panel]->modo = $rs["modo"];
			$this->elem[$panel]->registro = $rs["registro"];
			$this->elem[$panel]->pagina = $rs["pagina"];
			$this->elem[$panel]->para_obj = $rs["para_obj"];
			$this->elem[$panel]->dinamico = $rs["dinamico"];
			$this->elem[$panel]->clase_panel = $rs["clase"];
			$this->elem[$panel]->parametros = $rs["parametros"];
			$this->elem[$panel]->parametros = $this->evaluar_todo($this->elem[$panel]->parametros);
			if($prop = extraer_para($this->elem[$panel]->parametros)){
				$this->vpara = array_merge($this->vpara, $prop);
				foreach($prop as $para => $valor){
					$this->elem[$panel]->$para = $valor;
					
					//eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
		}// end if
		return $this->elem;
	}// end function
	//===========================================================
	function leer_diagrama($plantilla = ""){
		if($plantilla == ""){
			return "";
		}// end if
		$cn = &$this->conexion;
		$cn->query = "SELECT diagrama, archivo FROM $this->cfg_plantillas WHERE plantilla = '$plantilla'";
		
		if($rs = $cn->consultar($cn->ejecutar())){
			$archivo = C_PATH_PLANTILLAS.$rs["archivo"];
			switch ($this->tipo_plantilla){
			case C_PLANTILLA_DEFAULT:
				if($archivo != "" and $form = @file_get_contents($archivo)){
					return $form; 
				}else{
					return $rs["diagrama"];
				}// end if
				break;
			case C_PLANTILLA_DIAGRAMA:
				return $rs["diagrama"];
				break;
			case C_PLANTILLA_ARCHIVO:
				if($form = @file_get_contents($archivo)){
					return $form; 
				}// end if
				break;
			}// end switch
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