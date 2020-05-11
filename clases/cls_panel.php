<?php
//===========================================================
class cls_panel{
	var $actualizado = false;
	var $panel = 0;
	var $elemento = 0;
	var $nombre = "";
	
	var $modo = 0;
	var $registro = false;
	var $pagina = 1;
	var $para_obj = 1;
	var $dinamico = false;
	var $parametros = "";
	
	var $vista = "";
	var $formulario = "";
	
	var $sub_formulario = false;
	var $script = "";
	
	
	
	public $titulo = "";
	public $reg = "";
	public $nreg = false;
	public $mvt = "";
	public $ntreg = "";
	public $referencia = "";
	public $volver = false;
	public $clase_panel = false;
	public $ajax = 0;
	
	public $de_sesion = false;
	public $de_vsesion = false;
	
	public $hidden = false;
	
	
	public $ondebug = false;
	//===========================================================
	function control(){
		
		
		$control = "";
		$script_x = "";
		$obj = false;
		
		if($this->con_interaccion and $this->nreg != "" and $this->nreg != "0" and $this->nreg < $this->ntreg){
			$this->elemento = C_OBJ_FORMULARIO;
			$this->modo = C_MODO_UPDATE;
			$this->nombre = $this->formulario;
		}else{
			$this->nreg = 0;
		}// end if
		
		if(($this->vses["DEBUG"]=="1" and $this->panel != C_PANEL_DEBUG)){
			$this->ondebug = true;
		}
		
		//===========================================================
		$clase_x = ($this->clase_panel!="")?$this->clase_panel:$this->clase;
		$this->vses["SS_CLASE"] = $clase_x;
		$this->elemento = c_objeto($this->elemento);
		
		switch ($this->elemento){
			case C_OBJ_FORMULARIO:

				require_once("cls_formulario.php");
				$obj = new cls_formulario;
				$obj->ondebug = $this->ondebug;

				if(($this->vses["DEBUG"]=="1" and $this->panel != C_PANEL_DEBUG)){
					$obj->debug = 1;

					$obj->ondebug = true;
				}// end if

				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->vexp = &$this->vexp;
				$obj->deb = &$this->deb;
				$obj->panel = $this->panel;
				$obj->panel_default = $this->panel_default;
				$obj->clase = $clase_x;
				$obj->modo = &$this->modo;

				$obj->mele_script = $this->mele_script;
				$obj->ele_script = $this->ele_script;

				if($this->formulario=="" and isset($this->vform["cfg_formulario_aux"])){
					$this->formulario = $this->vform["cfg_formulario_aux"];
				}//


				if($this->reg){

					$reg_i = explode(C_SEP_Q,$this->reg);
					$this->registro = $reg_i[$this->nreg];
					$this->ntreg = count($reg_i);
					$this->nreg++;

				}// end if			

				$obj->registro = $this->registro;

				//$obj->titulo = $this->nombre;
				if ($this->pagina <= 0 or $this->pagina == ""){
					$this->pagina = 1;
				}// end if

				$this->vform["cfg_registro_aux"] = isset($this->vform["cfg_reg_aux"])?$this->vform["cfg_reg_aux"]:"";

				if($this->de_vsesion){
					$obj->de_sesion = true;
					$obj->con_valores = true;
					$obj->valores  = $this->vses[$this->de_vsesion];
				}// end if
				if($this->de_sesion and $this->formulario==$this->nombre){
					$obj->de_sesion = true;
					$obj->con_valores = true;
					$obj->valores  = $this->vses["VSFORM"];
				}// end if

				if($this->nombre=="" && $this->vista != ""){
					$volver = true;
				}else{
					$volver = false;
				}// end if


				if($this->nombre==""){
					$volver = true;
					$this->nombre = $this->formulario;
				}// end if




				$control = $obj->control($this->nombre);
				$this->titulo = $obj->titulo;
				$this->formulario = $obj->formulario;
				$this->sub_formulario = $obj->sub_formulario;

				if(!$volver and !$this->volver){
					if($obj->consulta){
						$this->vista = $obj->consulta;
					}else{
						$this->vista = $this->formulario;
					}// end if
				}// end if
				$script_x = $obj->script;
				$script_x .= $obj->validaciones;
				$this->nombre="";

				$this->sf_width = $obj->sf_width;

				break;

			case C_OBJ_CONSULTA:
				require_once ("cls_consulta.php");
				$obj = new cls_consulta;
				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->vexp = &$this->vexp;
				$obj->deb = &$this->deb;
				$obj->ondebug = $this->ondebug;

				$obj->panel = $this->panel;
				$obj->panel_default = $this->panel_default;
				$obj->clase = $clase_x;

				$obj->mele_script = $this->mele_script;
				$obj->ele_script = $this->ele_script;



				if($this->vista=="" && isset($this->vform["cfg_vista_aux"])){
					$this->vista = $this->vform["cfg_vista_aux"];
				}//



				$obj->sub_elem = &$this->sub_elem;
				$obj->pagina = $this->pagina;
				if($this->nombre=="" && $this->formulario != ""){
					$volver = true;
				}else{
					$volver = false;
				}// end if
				if($this->nombre==""){
					$this->nombre = $this->vista;
				}// end if

				$control = $obj->control($this->nombre);
				$this->titulo = $obj->titulo;
				$script_x = $obj->script;
				$script_x .= $obj->validaciones;
				$script_x .= $obj->script_load;
				$this->vista = $obj->consulta;
				//$this->formulario = $obj->formulario;
				if(!$volver and !$this->volver){

					if($obj->formulario){
						$this->formulario = $obj->formulario;
					}else{
						$this->formulario = $this->vista;
					}// end if
				}// end if
				$this->nombre="";
				$this->modo = "1";
				break;
			case C_OBJ_ARTICULO;

				require_once("cls_articulo.php");
				$obj = new cls_articulo;
				$obj->clase = $clase_x;
				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->deb = &$this->deb;
				$obj->ondebug = $this->ondebug;

				$control = $obj->control($this->nombre,$this->modo);
				$this->titulo = $obj->titulo;

				break;
			case C_OBJ_GRAFICO;

				require_once("cls_grafico2.php");
				$obj = new cls_grafico;
				$obj->clase = $clase_x;
				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->deb = &$this->deb;
				$obj->ondebug = $this->ondebug;
				//hhr($obj->vses["SS_PATH"]);

				$obj->panel = $this->panel;

				$control = $obj->control($this->nombre);
				$this->titulo = $obj->titulo;

				break;
			case C_OBJ_REPORTE:
				require_once("cls_reporte.php");
				$obj = new cls_reporte;
				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->vexp = &$this->vexp;

				$obj->panel = $this->panel;
				$obj->ondebug = $this->ondebug;
				$obj->deb = &$this->deb;
				$control = $obj->control($this->nombre);
				break;
			case C_OBJ_PAGINA:
				require_once ("cls_pagina.php");
				$obj = new cls_pagina;
				$control = $obj->control($st->parametro);
				$titulo = $obj->titulo;
				$this->js_post($obj->script,1);
				break;
				//
			case C_OBJ_CATALOGO:

				require_once("cls_catalogo.php");
				$obj = new cls_catalogo;
				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->vexp = &$this->vexp;

				$obj->panel = $this->panel;
				$obj->ondebug = $this->ondebug;
				$obj->deb = &$this->deb;
				$control = $obj->control($this->nombre);

				break;
			case C_OBJ_IFRAME:
				$control = get_include_contents($this->nombre);
				break;
				//$obj = new cls_element_html("iframe","marco");
				$obj->src = $this->nombre;
				$obj->frameborder = "0";
				//$obj->style->width = "100%";
				//$obj->style->height = "500";
				//$obj->scrolling="auto";
				$control = $obj->control();
				break;

			case C_OBJ_NINGUNO:
			case C_OBJ_NO_APLICA:
				$control = "";
				break;
				
				
			default:
				global $ELEMENTOS;
				
				if(isset($ELEMENTOS[$this->elemento])){
					$obj = new $ELEMENTOS[$this->elemento];
					$obj->panel = $this->panel;
					$obj->clase = $clase_x;
					
					$obj->vreq = &$this->vform;
					$obj->vses = &$this->vses;
					$obj->vexp = &$this->vexp;
					
					$obj->deb = &$this->deb;
					$obj->ondebug = $this->ondebug;

					$control = $obj->control($this->nombre, $this->modo);
					$this->titulo = $obj->titulo;
					$script_x = $obj->script;
				}
				
				//require_once("cls_articulo.php");
				
				break;
		}// end switch
  		$this->volver = false;
		$this->de_vsesion = false;
		$this->de_sesion = false;
		
		
		

		if($this->dinamico or $this->panel == $this->panel_default){
			
			$titulo = "";
			$eventos = false;
			if(isset($obj->titulo)){
				$titulo = $obj->titulo;
			}elseif(isset($obj->title)){
				$titulo = $obj->title;
			}
			if(isset($obj->eventos)){
				$eventos = $obj->eventos;
			}elseif(isset($obj->events)){
				$eventos = $obj->events;
			}
			
		
			if($obj and $this->panel == $this->panel_default and $titulo){
				
				$this->title = $obj->titulo;
					
			}// end if		
		
			$f = new cls_element_html("form",$this->ele_script);
			$f->enctype = "multipart/form-data";
			$f->method = $this->metodo;
			if($_SERVER['PHP_SELF']){
				$f->action = $_SERVER['PHP_SELF'];
			}else{
				$f->action = "index.php";
			}// end if
			$f->inner_html = $control.$this->var_cfg();
			$aux = $f->control();

			$this->script = "\n$this->mele_script = new cls_formulario(\"$this->ele_script\");\n";
			$this->script .= $script_x;
			



			$this->script .= "\n$this->mele_script.init();\n";
			if($obj and $eventos  and $prop = extraer_para($eventos)){
				
				foreach($prop as $para => $valor){
					if($para=="init"){
						$valor = str_replace("this.form",$this->mele_script.".f",$valor);
						$this->script .= $valor.";\n";
					
					}
					//eval("\$f->$para=\"$valor\";");
				}// next
			}// end if

		}else{
			$aux = $control;
		}// end if
		
		if($control == ""){
			//echo "1";exit;
			$this->hidden = true;
		}else{
			$this->hidden = false;
		}
		
		return $aux;
		
		/*
		$span = new cls_element_html("span");
		$span->id = "sg_panel_".$this->panel;
		
		if($control == ""){
			$span->style = "display:none";
		}
		$span->inner_html = $aux;

		
		
		
		
		return $span->control();	
		*/
	}// end function
	
	
	function var_cfg(){
	
	
		
		// ************* Parametros Variables *************************
		if($this->vses["DEBUG"] != "1" or 1==1){

			$this->ele = new cls_element_html("hidden");
			$this->ele->value = "";
			$this->ele->name = "";	
	
		
			$control = "";
			$ele = &$this->ele;



			$ele->name = "cfg_param_aux";
			$ele->value = "";
			$control .= $ele->control();
		
			$ele->name = "cfg_panel_aux";
			$ele->value = $this->panel;
			$control .= $ele->control();
	
			$ele->name = "cfg_objeto_aux";
			$ele->value = $this->elemento;
			$control .= $ele->control();
	
			$ele->name = "cfg_nombre_aux";
			$ele->value = $this->nombre;
			$control .= $ele->control();
	
			$ele->name = "cfg_modo_aux";
			$ele->value = $this->modo;
			$control .= $ele->control();
	
			$ele->name = "cfg_reg_aux";
			$ele->value = $this->reg;
			$control .= $ele->control();
	
			$ele->name = "cfg_registro_aux";
			$ele->value = $this->registro;
			$control .= $ele->control();

			$ele->name = "cfg_pagina_aux";
			$ele->value = $this->pagina;
			$control .= $ele->control();
	
			$ele->name = "cfg_formulario_aux";
			$ele->value = $this->formulario;
			$control .= $ele->control();
	
			$ele->name = "cfg_vista_aux";
			$ele->value = $this->vista;
			$control .= $ele->control();
	
			$ele->name = "cfg_parametros_aux";
			$ele->value = $this->parametros;
			$control .= $ele->control();
	
			$ele->name = "cfg_est_aux";
			$ele->value = $this->est;
			$control .= $ele->control();
	
			$ele->name = "cfg_referencia_aux";
			$ele->value = $this->referencia;
			$control .= $ele->control();


			$ele->name = "cfg_ins_aux";
			$ele->value = $this->ins;
			$control .= $ele->control();

			$ele->name = "cfg_tgt_aux";
			$ele->value = $this->tgt;
			$control .= $ele->control();
	
			$ele->name = "cfg_sw_aux";
			$ele->value = $this->sw;
			$control .= $ele->control();
	
			$ele->name = "cfg_sw2_aux";
			$ele->value = $this->sw2;
			$control .= $ele->control();
	
			$ele->name = "cfg_mvt_aux";
			$ele->value = $this->mvt;
			$control .= $ele->control();

			$ele->name = "cfg_nreg_aux";
			$ele->value = $this->nreg;
			$control .= $ele->control();

			$ele->name = "cfg_ntreg_aux";
			$ele->value = $this->ntreg;
			$control .= $ele->control();
	
			$ele->name = "cfg_hab_aux";
			$ele->value = "1";
			$control .= $ele->control();
			
			$ele->name = "cfg_async_aux";
			$ele->value = $this->ajax;
			$control .= $ele->control();
			
			if($this->ondebug){
				$ele->name = "cfg_db_aux";
				$ele->value = $this->ondebug;
				$control .= $ele->control();
				
			}
			
			
			
			
		}	
	
		return $control;
	}// end function


}
$ELEMENTOS = array();
function loadClsElement($clsElement){
	global $ELEMENTOS;
	foreach($clsElement as $k => $v){
		require_once($v["file"]);
		$ELEMENTOS[$k] = $v["class"];
		//$this->_actions[$k] = $v["class"]::listActions();
	}// next 

}// end function

if(isset($clsElement)){
	loadClsElement($clsElement);
}

?>