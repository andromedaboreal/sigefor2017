<?php
/*****************************************************************
creado: 18/04/2007
modificado: 20/11/2008
por: Yanny Nuñez
*****************************************************************/
class cfg_item{

	var $accion = "";
	var $titulo = "";
	var $panel = "";
	var $objeto = "";
	var $obj_nombre = "";
	var $modo = "";
	var $registro = "";
	var $pagina = "";
	var $referencia = "";
	var $target = "";
	
	var $tipo = "";
	var $clase = "";
	var $interaccion = "";
	var $validar = "";
	var $procedimeinto = "";
	var $estructura = "";
	var $parametros = "";
	var $estilo = "";
	var $propiedades = "";
	var $eventos = "";
	var $funcion = "";
	var $sesion = "";
	var $confirmar = "";
	var $mensaje = "";

	var $cmd_ini = "";
	var $cmd_fin = "";
	var $proc_ini = "";
	var $proc_fin = "";
	var $expresiones = "";
	var $separador = "";
	var $orden = "";
	var $status = "";

	
	var $oculto = "no";
	var $indicador = "si";
	var $abrir_menu = "";
	
	var $ajax = false;
	
	var $param = "";
	var $ref_obj = array(
		C_ELEM_NO_APLICA=>"",
		C_ELEM_FORMULARIO=>"formulario",
		C_ELEM_CONSULTA=>"consulta",
		C_ELEM_BUSQUEDA=>"busqueda",
		C_ELEM_REPORTE=>"reporte",
		C_ELEM_ARTICULO=>"articulo",
		C_ELEM_PAGINA=>"pagina",
		C_ELEM_ENLACE=>"enlace",
		C_ELEM_IFRAME=>"iframe",
		C_ELEM_MENU=>"menu",
		C_ELEM_VISTA=>"vista",
		C_ELEM_CATALOGO=>"catalogo",
		C_ELEM_COMANDO=>"comando",
		C_ELEM_ACCION=>"accion",
		C_ELEM_REFERENCIA=>"referencia",
		C_ELEM_NODO=>"nodo",
		C_ELEM_NINGUNO=>"ninguno",
		C_ELEM_GRAFICO=>"grafico",
		);
	//===========================================================
	var $evento = false;
	var $prop = false;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vexp = array();
	//===========================================================
	
	public $pag_form = false;
	public $variables  = false;
	public $cerrar_sesion = false;
	public $desautorizar = false;
	public $restablecer_est = false;
	public $en_sesion = false;
	
	public $guardar_form = false;
	public $de_sesion = false;
	public $de_vsesion = false;
	public $salir = false;
	public $volver = false;
	public $leer_config = false;
	public $panel_submit = false;
	
	public $clase_item = false;
	public $clase_regla = false;
	
	public $menu_orientacion = false;
	
	public $ini_est = "";
	public $autorizar  = false;
	public $menuest = false;
	public $even = false;
	public $window = false;
	public $vparams = false;
	/*public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	public $menu_orientacion = false;
	*/
	
	function ejecutar(&$rs){
	//hr($this->panel_default);
		$this->accion = $rs["accion"];
		$this->titulo = $rs["titulo"];
		
		if($rs["panel"]!="" and $rs["panel"]>0){
			
			$this->panel = $rs["panel"];
		}else if($rs["panel"]==0){
			$this->panel = $this->panel_default;   
		}else{
			$this->panel = $this->panel_actual;
		}// end if
		
		//hr("Panel: $this->panel ");
		$this->elemento = $rs["elemento"];
		$this->nombre = $rs["nombre"];
		$this->modo = $rs["modo"];
		$this->registro = $rs["registro"];
		$this->pagina = $rs["pagina"];
		$this->referencia = $rs["referencia"];
		$this->target = $rs["target"];
		$this->tipo = $rs["tipo"];
		$this->clase = $rs["clase"];
		$this->obj = $rs["obj"];
		$this->obj_funcion = $rs["obj_funcion"];
		$this->interaccion = $rs["interaccion"];
		$this->secuencia = $rs["secuencia"];
		$this->nodo = $rs["nodo"];
		$this->validar = $rs["validar"];
		$this->procedimiento = (isset($rs["procedimiento"]))?$rs["procedimiento"]:false;
		$this->estructura = $rs["estructura"];
		$this->parametros = $rs["parametros"];
		$this->estilo = $rs["estilo"];
		$this->propiedades = $rs["propiedades"];
		$this->eventos = $rs["eventos"];
		$this->funcion = $rs["funcion"];
		$this->sesion = $rs["sesion"];
		$this->confirmar = $rs["confirmar"];
		$this->mensaje = $rs["mensaje"];
		$this->cmd_ini = $rs["cmd_ini"];	
		$this->cmd_fin = $rs["cmd_fin"];	
		$this->proc_ini = $rs["proc_ini"];	
		$this->proc_fin = $rs["proc_fin"];	
		$this->expresiones = (isset($rs["expresiones"]))?$rs["expresiones"]:false;
		$this->separador = (isset($rs["separador"]))?$rs["separador"]:false;
		$this->orden = (isset($rs["orden"]))?$rs["orden"]:false;
		$this->status = $rs["status"];	
		//hr("accion: $this->titulo ($this->nombre)","#334455");
		//===========================================================
		$this->vpara = &$rs;
		//===========================================================
		$this->parametros = $this->evaluar_todo($this->parametros);
		if($prop = extraer_para($this->parametros)){
			$this->vpara = array_merge($this->vpara,$prop);
			foreach($prop as $para => $valor){
				eval("\$this->$para=\"$valor\";");
			}// next
		}// end if


		if(isset($this->vses["DEBUG"]) and $this->vses["DEBUG"] == "1"){
			if($this->oculto=="si"){
				$this->oculto = "";
				$this->titulo .= " [H]";
				$this->estilo .= ";color:#AAAAAA;";
			}// end if
		}// end if
		
		
		//===========================================================
		if($this->oculto=="si"){
			return false;
		}// end if
		//===========================================================
		$this->expresiones = $this->evaluar_todo($this->expresiones);
		if($prop = extraer_para($this->expresiones)){
			$this->vexp = array_merge($this->vexp, $prop);
		}// end if
		//===========================================================
		$param = "";
		
		
		
		if(isset($this->vses["DEBUG"]) and $this->vses["DEBUG"] == "1"){
			$this->deb->dbg($this->panel_actual,$this->accion,$this->titulo,"accion=$this->accion","a");
		}// end if



		if($this->elemento==C_ELEM_MENU){
			$this->vexp["PARAM"] =  $param;
			
			if($this->menu_orientacion==C_MENU_HORIZONTAL){
				$this->smenu_posicion = 0;
			}// end if
			
			$param = "onclick:$this->nombre.menu(this,$this->smenu_posicion);";//
			$this->abrir_menu = "onclick:$this->nombre.menu(this,$this->smenu_posicion,1);";//
		}elseif($this->elemento==C_ELEM_ENLACE){
			$this->url = str_replace(":","\:",$this->url);
			$this->even["onclick"] = "vinculo('$this->url','$this->target')";
			if($this->tipo!=2){
				$param = "onclick:vinculo('$this->url','$this->target')";//
			}// end if
			
			
		}else{
			
			$params = array();
			if ($this->panel!=""){
				$param .= "panel:".$this->panel.C_SEP_Q;
				$params["panel"] = $this->panel;
			}// end if
			if ($this->elemento!=C_ELEM_ENLACE 
				and $this->elemento!=C_ELEM_MENU){
				$param .= "elemento:".$this->getElemento($this->elemento).C_SEP_Q;
				
				$params["elemento"] = $this->getElemento($this->elemento);
			}// end if
			if ($this->nombre!=""){
				$param .= "nombre:".$this->nombre.C_SEP_Q;
				$params["nombre"] = $this->nombre;
			}// end if
			if ($this->modo!=""){
				$param .= "modo:".$this->modo.C_SEP_Q;
				$params["modo"] = $this->modo;
			}// end if
			if ($this->obj!=""){
				$param .= "obj:".$this->obj.C_SEP_Q;
				$params["obj"] = $this->obj;
			}// end if
			if ($this->obj_funcion!=""){
				$param .= "obj_funcion:".$this->obj_funcion.C_SEP_Q;
				$params["obj_funcion"] = $this->obj_funcion;
			}// end if
			if ($this->interaccion!=""){
				$param .= "interaccion:".$this->interaccion.C_SEP_Q;
				$params["interaccion"] = $this->interaccion;
			}// end if
			if ($this->secuencia!=""){
				$param .= "secuencia:".$this->secuencia.C_SEP_Q;
				$params["secuencia"] = $this->secuencia;
			}// end if
			if ($this->nodo!=""){
				$param .= "nodo:".$this->nodo.C_SEP_Q;
				$params["nodo"] = $this->nodo;
			}// end if
			if ($this->cmd_ini!=""){
				$param .= "cmd_ini:".$this->cmd_ini.C_SEP_Q;
				$params["cmd_ini"] = $this->cmd_ini;
			}// end if
			if ($this->cmd_fin!=""){
				$param .= "cmd_fin:".$this->cmd_fin.C_SEP_Q;
				$params["cmd_fin"] = $this->cmd_fin;
			}// end if
			if ($this->proc_ini!=""){
			
				$param .= "proc_ini:".$this->proc_ini.C_SEP_Q;
				$params["proc_ini"] = $this->proc_ini;
			}// end if
			if ($this->proc_fin!=""){
				$param .= "proc_fin:".$this->proc_fin.C_SEP_Q;
				$params["proc_fin"] = $this->proc_fin;
			}// end if
			if ($this->registro!=""){
				$param .= "registro:".$this->registro.C_SEP_Q;
				$params["registro"] = $this->registro;
			}// end if
			if ($this->estructura!=""){
				$param .= "estructura:".$this->estructura.C_SEP_Q;
				$params["estructura"] = $this->estructura;
				$this->ajax = false;
			}// end if
			if ($this->pagina!=""){
				$param .= "pagina:".$this->pagina.C_SEP_Q;
				$params["pagina"] = $this->pagina;
			}// end if
			if ($this->pag_form!=""){
				$param .= "pag_form:".$this->pag_form.C_SEP_Q;
				$params["pag_form"] = $this->pag_form;
			}// end if

			if ($this->expresiones != ""){
				$param .= "expresiones:".$this->expresiones.C_SEP_Q;
				$params["expresiones"] = $this->expresiones;
			}// end if
			if ($this->variables != ""){
				$param .= "variables:".$this->variables.C_SEP_Q;
				$params["variables"] = $this->variables;
			}// end if
			if ($this->cerrar_sesion != ""){
				$param .= "cerrar_sesion:1".C_SEP_Q;
				$params["cerrar_sesion"] = 1;
				$this->ajax = false;
			}// end if

			if ($this->autorizar == "si"){
				$param .= "autorizar:1".C_SEP_Q;
				$params["autorizar"] = 1;
				if ($this->ini_est == "si"){
					$param .= "ini_est:1".C_SEP_Q;
					$params["ini_est"] = 1;
				}// end if
				$this->ajax = false;
			}// end if
			if ($this->desautorizar == "si"){
				$param .= "desautorizar:1".C_SEP_Q;
				$params["desautorizar"] = 1;
				if ($this->ini_est == "si"){
					$param .= "ini_est:1".C_SEP_Q;
					$params["ini_est"] = 1;
				}// end if
				$this->ajax = false;
			}// end if
			if ($this->restablecer_est == "si"){
				$param .= "ini_est:1".C_SEP_Q;
				$params["ini_est"] = 1;
				$this->ajax = false;
			}// end if
			if ($this->en_sesion == "si"){
				$param .= "en_session:1".C_SEP_Q;
				$params["en_session"] = 1;
			}// end if
			if ($this->guardar_form){
				$param .= "guardar_form:$this->guardar_form".C_SEP_Q;
				$params["guardar_form"] = $this->guardar_form;
			}// end if
			if ($this->de_sesion == "si"){
				$param .= "de_sesion:1".C_SEP_Q;
				$params["de_sesion"] = 1;
			}// end if
			if ($this->de_vsesion){
				$param .= "de_vsesion:$this->de_vsesion".C_SEP_Q;
				$params["de_vsesion"] = $this->de_vsesion;
			}// end if
			if ($this->salir == "si"){
				$param .= "salir:1".C_SEP_Q;
				$params["salir"] = 1;
				$this->ajax = false;
			}// end if
			if ($this->volver == "si"){
				$param .= "volver:1".C_SEP_Q;
				$params["volver"] = 1;
			}// end if
			if ($this->leer_config == "si"){
				$param .= "leer_config:1".C_SEP_Q;
				$params["leer_config"] = 1;
			}// end if
			if ($this->ajax == "si"){
				$param .= "ajax:1".C_SEP_Q;
			}// end if

			if ($this->referencia!=""){
				$this->panel = $rs["referencia"];			
				$param .= "ref:".$this->referencia.C_SEP_Q;
			}// end if
			$param = str_replace(":","\:",$param);
			$param = str_replace(";","\;",$param);
			$this->vexp["PARAM"] =  $param;
			$confirmar = "";
			if($this->confirmar != ""){
				//$confirmar = " && confirmar(this,'" .$this->confirmar."')";
				$confirmar = str_replace(chr(10),"\\\\"."n",$this->confirmar);
				$confirmar = str_replace(chr(13),"",$confirmar);
			}// end if
			
			if($this->panel_submit == ""){
				$this->panel_submit = $this->panel;
			}// end if
			
			//$this->even["onclick"] = "return (enviar(this,'$this->panel_submit','$param','$this->validar','$confirmar'))";
			if($this->tipo!=2){
				//$param = "onclick:return (enviar(this,'$this->panel_submit','$param','$this->validar','$confirmar'))";//
			}// end if
			
			
				
			if($this->tipo != 2){
				
				
				
				$opt = new stdClass;
				$opt->async = ($this->ajax)?true:false;
				$opt->panel = $this->panel_submit;
				$opt->valid = $this->validar;
				$opt->confirm = $confirmar;

				if($this->target){
					$opt->target = $this->target;
				}

				if($this->vparams){
					$opt->vparams = $this->vparams;
				}

				if($this->window){
					$opt->window = $this->window;
				}
				$opt->params = $params;
				$json = json_encode($opt);

				$this->even["onclick"] = ("return sgPanel.send(this, $json);");
				if($this->tipo!=2){
					//$param = ("onclick:sgPanel.send(this, $json)");
				}// end if
			}else{
				
			}	
				
				

			
			
		}// end if
		
		
		if($prop = extraer_para($this->evaluar_todo($this->eventos))){
			
			foreach($prop as $para => $valor){
				if(isset($this->even[$para])){
					$this->even[$para] .= $valor.";";
				}else{
					$this->even[$para] = $valor.";";
				}
				
				
			}
			
			
		}
		
		$this->vexp["EVEN_PARAM"] =  $param;
		$this->eventos = ajustar_sep($this->eventos);
		//===========================================================
		$this->estilo = $this->evaluar_var($this->estilo);
		$this->propiedades = $this->evaluar_var($this->propiedades);
		$this->eventos = $this->evaluar_var($this->eventos);
		
		return true;
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
	
	public function getElemento($elem){
		
		if(isset($this->ref_obj[$elem])){
			return $this->ref_obj[$elem];
		}
		return $elem;
		
	}
	
}// end class
?>